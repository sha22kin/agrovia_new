<?php

namespace App\Http\Controllers\Expert;

use Auth;
use File;
use Str;
use Carbon\Carbon;
use App\Models\Attribute;
use App\Models\Media;
use App\Models\PostAttribute;
use App\Models\BuyerPreference;
use App\Models\Consultation;
use App\Models\ExpertAnswer;
use App\Models\ExpertAvailability;
use App\Models\ExpertProfile;
use App\Models\ExpertTransaction;
use App\Models\PaymentMethod;
use App\Models\ExpertQuestion;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesAccount;
use App\Http\Controllers\Concerns\HandlesCommunity;
use App\Http\Controllers\Concerns\HandlesMessages;
use App\Http\Controllers\Concerns\HandlesPaymentMethods;
use App\Http\Controllers\Concerns\HandlesSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExpertController extends Controller
{
    use HandlesAccount, HandlesMessages, HandlesCommunity, HandlesSettings, HandlesPaymentMethods;

    // Pages that wait for their design; the sidebar already links to them.
    public const SOON = [
        'reviews' => 'Reviews',
    ];

    // Expert home: banner, numbers, upcoming consultations, questions to answer, specializations, latest article.
    public function dashboard(Request $request){

        $user =Auth::user();
        $profile =ExpertProfile::where('user_id',$user->id)->first();

        $sessions =Consultation::where('expert_id',$user->id);
        $rated =(clone $sessions)->where('status','completed')->whereNotNull('rating');
        $articles =Post::where('type',1)->where('addedby_id',$user->id)->where('status','active');

        $stats =[
            'consultations' => (clone $sessions)->whereIn('status',['confirmed','completed'])->count(),
            'upcoming' => (clone $sessions)->upcoming()->count(),
            'earnings' => (clone $sessions)->where('status','completed')->sum('fee'),
            'articles' => (clone $articles)->count(),
            'rating' => $rated->count()?round($rated->avg('rating'),1):null,
            'reviews' => $rated->count(),
        ];

        $upcoming =(clone $sessions)->upcoming()->with('farmer:id,name')->orderBy('scheduled_at')->take(3)->get();
        // The questions of the expert's specializations come first, like on the Q&A page.
        $questions =ExpertQuestion::toAnswer($user->id)->with('user:id,name')->specializedFirst($profile?->specializationList() ?: [])->latest()->latest('id')->take(3)->get();
        $article =(clone $articles)->with('imageFile')->latest()->first();
        $location =collect([$user->districtN?->name ?: $user->cityN?->name,'Bangladesh'])->filter()->implode(', ');
        $currency =general()->currency ?: '৳';

        return view(expertTheme().'dashboard',compact('user','profile','stats','upcoming','questions','article','location','currency'));
    }

    // The sessions of the expert: upcoming (waiting for an answer or confirmed), completed and cancelled.
    public function consultations(Request $request){

        $user =Auth::user();

        $groups =['upcoming'=>['pending','confirmed'],'completed'=>['completed'],'cancelled'=>['cancelled']];
        $status =array_key_exists($request->status,$groups)?$request->status:'upcoming';

        $mine =Consultation::where('expert_id',$user->id);
        $perStatus =(clone $mine)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status');
        $counts =[];
        foreach($groups as $key=>$statuses){
            $counts[$key] =$perStatus->only($statuses)->sum();
        }

        $consultations =(clone $mine)->whereIn('status',$groups[$status])->with('farmer:id,name,mobile')
            ->orderBy('scheduled_at',$status=='upcoming'?'asc':'desc')->orderBy('id',$status=='upcoming'?'asc':'desc')->paginate(10)->withQueryString();
        $currency =general()->currency ?: '৳';

        return view(expertTheme().'consultations',compact('consultations','status','counts','currency'));
    }

    // Accept or decline a request, mark a session as done, or cancel it.
    public function consultationAction(Request $r,$id,$action){

        $user =Auth::user();

        $consultation =Consultation::where('expert_id',$user->id)->with('farmer:id,name')->find($id);
        if(!$consultation){
            Session()->flash('error','This consultation is not found');
            return redirect()->route('expert.consultations');
        }

        $name =$consultation->farmer?->name ?: 'the farmer';
        $status =$consultation->status;

        if($action=='accept' && $status=='pending'){
            $consultation->status ='confirmed';
            $message ='The consultation with '.$name.' is confirmed';
        }elseif($action=='decline' && $status=='pending'){
            $consultation->status ='cancelled';
            $message ='The request of '.$name.' is declined';
        }elseif($action=='cancel' && $status=='confirmed'){
            $consultation->status ='cancelled';
            $message ='The consultation with '.$name.' is cancelled';
        }elseif($action=='complete' && $status=='confirmed' && $consultation->scheduled_at && $consultation->scheduled_at->isPast()){
            $consultation->status ='completed';
            $message ='The consultation with '.$name.' is marked as completed';
        }else{
            Session()->flash('error','This action is not possible for this consultation');
            return redirect()->back();
        }
        $consultation->save();

        // A completed session is income for the expert.
        ExpertTransaction::recordConsultation($consultation);

        Session()->flash('success',$message);
        return redirect()->back();
    }

    // Ask an Expert: the questions still to answer (the ones of my specializations first) and the ones I answered.
    public function qa(Request $request){

        $user =Auth::user();

        $profile =ExpertProfile::where('user_id',$user->id)->first();
        $specializations =$profile?->specializationList() ?: [];
        $status =$request->status=='answered'?'answered':'unanswered';

        $counts =[
            'unanswered' => ExpertQuestion::toAnswer($user->id)->count(),
            'answered' => ExpertAnswer::where('expert_id',$user->id)->whereHas('question')->count(),
        ];

        if($status=='answered'){
            $items =ExpertAnswer::where('expert_id',$user->id)->whereHas('question')->with('question.user:id,name')->latest()->latest('id')->paginate(10)->withQueryString();
        }else{
            $items =ExpertQuestion::toAnswer($user->id)->with('user:id,name')->withCount('answers')->specializedFirst($specializations)->latest()->latest('id')->paginate(10)->withQueryString();
        }

        return view(expertTheme().'qa',compact('items','status','counts','profile','specializations'));
    }

    // Change an answer the expert gave.
    public function answerUpdate(Request $r,$id){

        $r->validate(['body' => 'required|string|max:5000']);

        $answer =ExpertAnswer::where('expert_id',Auth::id())->find($id);
        if(!$answer){
            Session()->flash('error','This answer is not found');
            return redirect()->route('expert.qa',['status'=>'answered']);
        }

        $answer->body =trim($r->body);
        $answer->save();

        Session()->flash('success','Your answer is updated');
        return redirect()->back();
    }

    // Earnings: the money of the expert (income, fees, withdrawals), the balance and this month.
    public function earnings(Request $request){

        $user =Auth::user();

        ExpertTransaction::syncFor($user->id);

        $transactions =ExpertTransaction::where('expert_id',$user->id)->latest()->latest('id')->paginate(10)->withQueryString();
        $balance =ExpertTransaction::balanceOf($user->id);
        $pending =ExpertTransaction::pendingWithdrawalsOf($user->id);

        $income =ExpertTransaction::where('expert_id',$user->id)->where('type','consultation')->where('created_at','>=',now()->startOfMonth());
        $month =['sessions'=>(clone $income)->count(),'earned'=>(float) (clone $income)->sum('amount')];

        $methods =PaymentMethod::where('user_id',$user->id)->orderByDesc('is_default')->orderByDesc('id')->get();
        $min =(float) config('expert.min_withdrawal');
        $currency =general()->currency ?: '৳';

        return view(expertTheme().'earnings',compact('transactions','balance','pending','month','methods','min','currency'));
    }

    // Ask to be paid: the amount is taken from the balance at once and paid out later.
    public function withdraw(Request $r){

        $user =Auth::user();
        $min =(float) config('expert.min_withdrawal');

        $r->validate([
            'amount' => 'required|numeric|min:'.$min.'|max:9999999999',
            'payment_method' => 'required|integer',
        ],[
            'amount.min' => 'The smallest withdrawal is '.(general()->currency ?: '৳').' '.number_format($min).'.',
            'payment_method.required' => 'Choose the account you want to be paid on.',
        ]);

        $method =PaymentMethod::where('user_id',$user->id)->find($r->payment_method);
        if(!$method){
            return back()->withInput()->withErrors(['payment_method'=>'Choose one of your payout accounts.']);
        }

        // The balance is read and the request is written together, so two requests cannot spend the same money.
        $error =DB::transaction(function () use ($user,$r,$method) {
            ExpertTransaction::where('expert_id',$user->id)->lockForUpdate()->get(['id']);
            $balance =ExpertTransaction::balanceOf($user->id);
            $amount =round((float) $r->amount,2);

            if($amount>$balance){
                return 'You can withdraw up to '.(general()->currency ?: '৳').' '.number_format($balance,2).'.';
            }

            $row =new ExpertTransaction();
            $row->expert_id =$user->id;
            $row->type ='withdrawal';
            $row->amount =-$amount;
            $row->description ='Withdrawal to '.$method->title();
            $row->status ='pending';
            $row->save();

            return null;
        });

        if($error){
            return back()->withInput()->withErrors(['amount'=>$error]);
        }

        Session()->flash('success','Withdrawal of '.(general()->currency ?: '৳').' '.number_format((float) $r->amount).' is requested');
        return redirect()->route('expert.earnings');
    }

    // Take back a withdrawal that is not paid yet: the money returns to the balance.
    public function withdrawCancel(Request $r,$id){

        $row =ExpertTransaction::where('expert_id',Auth::id())->where('type','withdrawal')->where('status','pending')->find($id);
        if(!$row){
            Session()->flash('error','This withdrawal cannot be cancelled');
            return redirect()->route('expert.earnings');
        }

        $row->status ='cancelled';
        $row->save();

        Session()->flash('success','The withdrawal is cancelled and the money is back in your balance');
        return redirect()->route('expert.earnings');
    }

    // The accounts the expert is paid on (the same page as the buyer and the seller have).
    public function paymentMethods(Request $r){
        return $this->sharedPaymentMethods($r,'expert');
    }

    public function paymentMethodAction(Request $r,$action,$id=null){
        return $this->sharedPaymentMethodAction($r,'expert',$action,$id);
    }

    // Availability: the hours of each week day farmers can book, the session length and the fee.
    public function availability(Request $request){

        $user =Auth::user();

        $slots =ExpertAvailability::where('expert_id',$user->id)->orderBy('start_time')->get()->groupBy('day_of_week');
        $profile =ExpertProfile::forUser($user->id);
        $currency =general()->currency ?: '৳';

        return view(expertTheme().'availability',compact('slots','profile','currency'));
    }

    // Add one time slot to one or more week days.
    public function availabilitySlotAdd(Request $r){

        $user =Auth::user();

        $r->validate([
            'days' => 'required|array|min:1',
            'days.*' => 'integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ],[
            'days.required' => 'Choose at least one day.',
            'end_time.after' => 'The end time must be after the start time.',
        ]);

        $start =Carbon::createFromFormat('H:i',$r->start_time);
        $end =Carbon::createFromFormat('H:i',$r->end_time);
        if($start->diffInMinutes($end)<ExpertAvailability::MIN_MINUTES){
            return back()->withInput()->withErrors(['end_time'=>'A time slot must be at least '.ExpertAvailability::MIN_MINUTES.' minutes long.']);
        }

        $days =array_values(array_unique(array_map('intval',$r->days)));
        $existing =ExpertAvailability::where('expert_id',$user->id)->whereIn('day_of_week',$days)->get()->groupBy('day_of_week');

        // Nothing is saved when one of the days does not fit.
        foreach($days as $day){
            $slots =$existing->get($day,collect());
            $clash =$slots->first(fn ($slot) => $r->start_time.':00' < $slot->end_time && $r->end_time.':00' > $slot->start_time);
            if($clash){
                return back()->withInput()->withErrors(['days'=>ExpertAvailability::DAYS[$day].' already has '.$clash->label().', which overlaps this time.']);
            }
            if($slots->count()>=ExpertAvailability::MAX_PER_DAY){
                return back()->withInput()->withErrors(['days'=>ExpertAvailability::DAYS[$day].' already has '.ExpertAvailability::MAX_PER_DAY.' time slots.']);
            }
        }

        foreach($days as $day){
            $slot =new ExpertAvailability();
            $slot->expert_id =$user->id;
            $slot->day_of_week =$day;
            $slot->start_time =$r->start_time;
            $slot->end_time =$r->end_time;
            $slot->save();
        }

        $label =$slot->label();
        Session()->flash('success','Time slot '.$label.' added for '.collect($days)->sort()->map(fn ($d) => ExpertAvailability::DAYS[$d])->implode(', '));
        return redirect()->route('expert.availability');
    }

    public function availabilitySlotDelete(Request $r,$id){

        $slot =ExpertAvailability::where('expert_id',Auth::id())->find($id);
        if(!$slot){
            Session()->flash('error','This time slot is not found');
            return redirect()->route('expert.availability');
        }

        $message ='Time slot '.$slot->label().' removed from '.ExpertAvailability::DAYS[$slot->day_of_week];
        $slot->delete();

        Session()->flash('success',$message);
        return redirect()->route('expert.availability');
    }

    // Session length and fee (the fee is the one of the profile page too).
    public function availabilitySettings(Request $r){

        $r->validate([
            'session_minutes' => ['required',Rule::in(ExpertProfile::SESSION_MINUTES)],
            'fee' => 'nullable|numeric|min:0|max:9999999999',
        ]);

        $profile =ExpertProfile::forUser(Auth::id());
        $profile->session_minutes =$r->session_minutes;
        $profile->fee =$r->filled('fee')?$r->fee:null;
        $profile->save();

        Session()->flash('success','Your consultation settings are saved');
        return redirect()->route('expert.availability');
    }

    // My Articles: the blog posts (type 1) of the expert, published (active) or draft (inactive).
    public function articles(Request $request){

        $articles =$this->articlesOf(Auth::user())->with('imageFile')->latest()->latest('id')->paginate(9)->withQueryString();

        return view(expertTheme().'articles',compact('articles'));
    }

    // Write, change and delete an article. The cover image is stored as WebP in the media table.
    public function articleAction(Request $r,$action,$id=null){

        $user =Auth::user();

        $post =null;
        if($action!='create'){
            $post =$this->articlesOf($user)->find($id);
            if(!$post){
                Session()->flash('error','This article is not found');
                return redirect()->route('expert.articles');
            }
        }

        if($action=='delete' && $r->isMethod('post')){
            foreach(Media::where('src_type',1)->where('src_id',$post->id)->get() as $media){
                foreach(array_unique(array_filter([$media->file_url,$media->file_url_sm,$media->file_url_md,$media->file_url_lg])) as $file){
                    if(File::exists(base_path($file))){
                        File::delete(base_path($file));
                    }
                }
                $media->delete();
            }
            $post->postCtgs()->delete();
            $post->postTags()->delete();
            $post->postComments()->delete();
            $post->delete();

            Session()->flash('success','Your article is deleted');
            return redirect()->route('expert.articles');
        }

        if(($action=='create' || $action=='edit') && $r->isMethod('post')){
            $r->validate([
                'name' => 'required|max:191',
                'short_description' => 'nullable|max:500',
                'description' => 'nullable|max:200000',
                'categories' => 'nullable|array',
                'categories.*' => ['numeric',Rule::exists('attributes','id')->where(fn ($q) => $q->where('type',6)->where('status','<>','temp'))],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            ]);

            $publish =$r->has('publish');
            $body =sanitizeArticleHtml($r->description);
            if($publish && mb_strlen(trim(strip_tags($body)))<30){
                return back()->withInput()->withErrors(['description'=>'Write at least 30 characters before you publish.']);
            }

            $wasActive =$post && $post->status=='active';
            if(!$post){
                $post =new Post();
                $post->type =1;
                $post->addedby_id =$user->id;
            }
            $post->name =$r->name;
            $post->short_description =trim(strip_tags((string) $r->short_description))?:null;
            $post->description =$body;
            $post->status =$publish?'active':'inactive';
            // The date of an article is the day it went live.
            if($publish && !$wasActive){
                $post->created_at =Carbon::now();
            }
            $post->editedby_id =$user->id;
            $post->save();

            // The address of the article stays the same once it exists.
            if(!$post->slug){
                $slug =Str::slug($post->name) ?: (string) $post->id;
                if(Post::where('type',1)->where('slug',$slug)->where('id','<>',$post->id)->exists()){
                    $slug .='-'.$post->id;
                }
                $post->slug =$slug;
                $post->save();
            }

            if($r->hasFile('image')){
                uploadImageAsWebp($r->file('image'),$post->id,1,1,$user->id);
            }

            $categoryIds =array_values(array_unique((array) $r->categories));
            $post->postCtgs()->whereNotIn('reff_id',$categoryIds)->delete();
            foreach($categoryIds as $i=>$categoryId){
                $link =$post->postCtgs()->where('reff_id',$categoryId)->first();
                if(!$link){
                    $link =new PostAttribute();
                    $link->src_id =$post->id;
                    $link->reff_id =$categoryId;
                    $link->type =1;
                }
                $link->drag =$i;
                $link->save();
            }

            Session()->flash('success',$publish?($wasActive?'Your article is updated':'Your article is published'):'Your draft is saved');
            return redirect()->route('expert.articles');
        }

        $categories =Attribute::where('type',6)->where('status','<>','temp')->whereNull('parent_id')->orderBy('name')->get(['id','name']);
        $selected =$post?$post->postCtgs()->pluck('reff_id')->all():[];

        return view(expertTheme().'articleForm',compact('post','categories','selected'));
    }

    private function articlesOf($user){
        return Post::where('type',1)->where('addedby_id',$user->id)->whereIn('status',['active','inactive']);
    }

    // Answer a question of a farmer (dashboard and Q&A page).
    public function questionAnswer(Request $r,$id){

        $user =Auth::user();

        $r->validate(['body' => 'required|string|max:5000']);

        $question =ExpertQuestion::toAnswer($user->id)->find($id);
        if(!$question){
            Session()->flash('error','This question is not available. It may be answered or closed already');
            return redirect()->route('expert.dashboard');
        }

        $answer =new ExpertAnswer();
        $answer->question_id =$question->id;
        $answer->expert_id =$user->id;
        $answer->body =trim($r->body);
        $answer->save();

        Session()->flash('success','Your answer is posted');
        return redirect()->back();
    }

    // Move a consultation to another time.
    public function consultationReschedule(Request $r,$id){

        $user =Auth::user();

        $r->validate(['scheduled_at' => 'required|date|after:now']);

        $consultation =Consultation::where('expert_id',$user->id)->whereIn('status',['pending','confirmed'])->find($id);
        if(!$consultation){
            Session()->flash('error','This consultation cannot be rescheduled');
            return redirect()->route('expert.dashboard');
        }

        $consultation->scheduled_at =Carbon::parse($r->scheduled_at);
        $consultation->save();

        Session()->flash('success','The consultation is moved to '.$consultation->scheduled_at->format('M d, g:i A'));
        return redirect()->route('expert.dashboard');
    }

    // Title, specializations and fee shown on the dashboard.
    public function profileDetails(Request $r){

        $user =Auth::user();

        $r->validate([
            'headline' => 'nullable|max:150',
            'specializations' => 'nullable|max:300',
            'fee' => 'nullable|numeric|min:0|max:9999999999',
        ]);

        $profile =ExpertProfile::forUser($user->id);
        $profile->headline =trim((string) $r->headline)?:null;
        $profile->specializations =BuyerPreference::cleanInterests($r->specializations);
        $profile->fee =$r->filled('fee')?$r->fee:null;
        $profile->save();

        Session()->flash('success','Your expert details are saved');
        return redirect()->route('expert.profile');
    }

    public function profile(Request $r){

        $response =$this->accountProfile($r,expertTheme().'profile');
        if($response instanceof \Illuminate\Http\RedirectResponse){
            return $response;
        }

        return $response->with('profile',ExpertProfile::forUser(Auth::id()));
    }

    public function profilePhoto(Request $r){
        return $this->accountPhoto($r);
    }

    // The "Reset Password" button lives on the settings page; only its POST is handled here.
    public function changePassword(Request $r){
        if(!$r->isMethod('post')){
            return redirect()->route('expert.settings');
        }
        return $this->accountRequestPasswordReset($r);
    }

    public function settings(Request $r){
        return $this->sharedSettings($r,'expert');
    }

    public function settingsToggle(Request $r){
        return $this->sharedSettingsToggle($r);
    }

    public function settingsPreferences(Request $r){
        return $this->sharedSettingsPreferences($r,'expert');
    }

    public function deactivate(Request $r){
        return $this->sharedDeactivate($r);
    }

    public function community(Request $r){
        return $this->communityList($r,'expert',expertTheme().'community');
    }

    public function communityAction(Request $r,$action,$id=null){
        return $this->communityHandle($r,'expert',expertTheme().'community',$action,$id);
    }

    public function messages(Request $r){
        return $this->messagesPage($r,'expert',expertTheme().'messages');
    }

    public function messageSend(Request $r){
        return $this->messagesSend($r,'expert');
    }

    public function messagePoll(Request $r){
        return $this->messagesPoll($r,'expert');
    }

    public function messageAction(Request $r){
        return $this->messagesAction($r,'expert');
    }

    public function soon(Request $r,$page){
        abort_unless(isset(self::SOON[$page]),404);

        return view(expertTheme().'soon',['title'=>self::SOON[$page]]);
    }
}
