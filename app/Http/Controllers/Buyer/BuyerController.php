<?php

namespace App\Http\Controllers\Buyer;

use Auth;
use App\Models\Address;
use App\Models\Attribute;
use App\Models\BusinessDocument;
use App\Models\BusinessProfile;
use App\Models\BuyerPreference;
use App\Models\Country;
use App\Models\Enquiry;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\SavedSearch;
use App\Models\Transaction;
use App\Models\UserSetting;
use App\Models\Wishlist;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesAccount;
use App\Http\Controllers\Concerns\HandlesAddresses;
use App\Http\Controllers\Concerns\HandlesBusiness;
use App\Http\Controllers\Concerns\HandlesMarketplace;
use App\Http\Controllers\Concerns\HandlesPaymentMethods;
use App\Http\Controllers\Concerns\HandlesSettings;
use App\Http\Controllers\Concerns\BuildsDashboard;
use App\Http\Controllers\Concerns\HandlesCommunity;
use App\Http\Controllers\Concerns\HandlesMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BuyerController extends Controller
{
    use HandlesAccount, BuildsDashboard, HandlesMessages, HandlesCommunity, HandlesAddresses, HandlesPaymentMethods, HandlesBusiness, HandlesSettings, HandlesMarketplace;

    // Buyer home: welcome banner, numbers, recent orders and what happened lately.
    public function dashboard(Request $request){

        $user =Auth::user();

        $stats =$this->buyerStats($user);
        $stats['ads'] =$this->enquiriesOf($user)->active()->count();

        $recentOrders =$this->ordersOf($user)->latest()->take(4)->get();
        $orderAds =$this->adsOfOrders($recentOrders);
        $recentEnquiries =$this->enquiriesOf($user)->withCount('quotes')->latest()->take(3)->get();

        $reviews =$this->reviewsOf($user);
        $activity =$this->activityOf($user,'buyer',$reviews)->take(4);
        $summary =$this->profileSummary($user,'buyer');

        return view(buyerTheme().'dashboard',compact('user','summary','stats','recentOrders','orderAds','recentEnquiries','activity'));
    }

    // The public profile page: banner, business info, verification, activity and reviews.
    public function profileOverview(Request $request){

        $user =Auth::user();

        $stats =$this->buyerStats($user);

        $recentOrders =$this->ordersOf($user)->latest()->take(4)->get();
        $orderAds =$this->adsOfOrders($recentOrders);
        $recentEnquiries =$this->enquiriesOf($user)->withCount('quotes')->latest()->take(3)->get();
        $preferences =BuyerPreference::where('user_id',$user->id)->first();

        $reviews =$this->reviewsOf($user);
        $activity =$this->activityOf($user,'buyer',$reviews);
        $summary =$this->profileSummary($user,'buyer');

        return view(buyerTheme().'profileOverview',compact('user','summary','stats','recentOrders','orderAds','recentEnquiries','preferences','reviews','activity'));
    }

    // What the buyer usually looks for, shown on the profile overview.
    public function preferences(Request $r){

        $user =Auth::user();
        $preferences =BuyerPreference::where('user_id',$user->id)->first() ?: new BuyerPreference();

        if($r->isMethod('post')){
            $r->validate([
                'interests' => 'nullable|max:300',
                'quality' => ['nullable',Rule::in(BuyerPreference::QUALITIES)],
                'quantity' => ['nullable',Rule::in(BuyerPreference::QUANTITIES)],
                'delivery' => ['nullable',Rule::in(BuyerPreference::DELIVERIES)],
                'budget_min' => 'nullable|numeric|min:0|max:9999999999',
                // gte only makes sense when a minimum was given.
                'budget_max' => ['nullable','numeric','min:0','max:9999999999',$r->filled('budget_min')?'gte:budget_min':'nullable'],
            ]);

            $preferences->user_id =$user->id;
            $preferences->interests =BuyerPreference::cleanInterests($r->interests);
            $preferences->quality =$r->quality?:null;
            $preferences->quantity =$r->quantity?:null;
            $preferences->delivery =$r->delivery?:null;
            $preferences->budget_min =$r->filled('budget_min')?$r->budget_min:null;
            $preferences->budget_max =$r->filled('budget_max')?$r->budget_max:null;
            $preferences->save();

            Session()->flash('success','Your buying preferences are saved');
            return redirect()->route('buyer.profileOverview');
        }

        return view(buyerTheme().'preferences',compact('preferences'));
    }

    public function orders(Request $request){

        $user =Auth::user();

        // ?status= is one of the Transaction::STATUS_GROUPS names.
        $status =array_key_exists($request->status,Transaction::STATUS_GROUPS)?$request->status:null;

        // Number of orders per status group for the filter pills.
        $perStatus =$this->ordersOf($user)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status');
        $counts =['All'=>$perStatus->sum()];
        foreach(Transaction::STATUS_GROUPS as $group=>$statuses){
            $counts[$group] =$perStatus->only($statuses)->sum();
        }

        $orders =$this->ordersOf($user)->when($status,fn ($q) => $q->whereIn('status',Transaction::STATUS_GROUPS[$status]))->latest()->paginate(10)->withQueryString();
        $orderAds =$this->adsOfOrders($orders);
        $summary =$this->profileSummary($user,'buyer');

        return $this->dashboardPage('buyer','My Orders','orders',compact('orders','orderAds','summary','status','counts'));
    }

    // My Ads: the buying requirements (enquiries) the buyer posted, with their state.
    public function ads(Request $r){

        $ads =$this->enquiriesOf(Auth::user())->withCount('quotes')->latest()->latest('id')->paginate(10);

        return view(buyerTheme().'ads',compact('ads'));
    }

    // Edit, renew and delete of a requirement.
    public function adsAction(Request $r,$action,$id=null){

        $ad =$this->enquiriesOf(Auth::user())->find($id);
        if(!$ad){
            Session()->flash('error','This ad is not found');
            return redirect()->route('buyer.ads');
        }

        if($action=='edit'){
            if($r->isMethod('post')){
                $r->validate($this->enquiryRules());

                $ad->title =$r->title;
                $ad->category_id =$r->category?:null;
                $ad->quantity =$r->quantity;
                $ad->unit =$r->unit;
                $ad->description =$r->description;
                // An edited requirement is reviewed again before sellers see it.
                $ad->sendForReview();
                $ad->save();

                Session()->flash('success','Your ad is updated and sent to the admin for approval');
                return redirect()->route('buyer.ads');
            }

            $categories =Attribute::where('type',0)->where('status','active')->whereNull('parent_id')->orderBy('name')->get(['id','name']);

            return view(buyerTheme().'enquiryForm',['enquiry'=>$ad,'categories'=>$categories]);
        }

        if($action=='renew' && $r->isMethod('post')){
            $ad->status ='open';
            $ad->expires_at =now()->addDays(Enquiry::EXPIRY_DAYS);
            $ad->save();

            Session()->flash('success','Your ad is active again for '.Enquiry::EXPIRY_DAYS.' days');
        }

        if($action=='delete' && $r->isMethod('post')){
            $ad->quotes()->delete();
            $ad->delete();

            Session()->flash('success','Your ad is deleted');
        }

        return redirect()->route('buyer.ads');
    }

    public function enquiries(Request $request){

        $user =Auth::user();

        // ?status= is one of the Enquiry::FILTERS keys.
        $status =array_key_exists($request->status,Enquiry::FILTERS)?$request->status:null;

        $counts =['all'=>$this->enquiriesOf($user)->count()];
        foreach(Enquiry::FILTERS as $key=>$label){
            $counts[$key] =$this->enquiriesOf($user)->filter($key)->count();
        }

        $enquiries =$this->enquiriesOf($user)->filter($status)->withCount('quotes')->latest()->paginate(10)->withQueryString();

        return view(buyerTheme().'enquiries',compact('enquiries','status','counts'));
    }

    public function enquiriesAction(Request $r,$action,$id=null){

        $user =Auth::user();

        if($action=='create'){

            if($r->isMethod('post')){
                $r->validate($this->enquiryRules());

                $enquiry =new Enquiry();
                $enquiry->user_id =$user->id;
                $enquiry->title =$r->title;
                $enquiry->category_id =$r->category?:null;
                $enquiry->quantity =$r->quantity;
                $enquiry->unit =$r->unit;
                $enquiry->description =$r->description;
                $enquiry->status ='open';
                $enquiry->sendForReview();
                $enquiry->expires_at =now()->addDays(Enquiry::EXPIRY_DAYS);
                $enquiry->save();

                Session()->flash('success','Your enquiry is submitted. It will be shown to sellers after the admin approves it');
                return redirect()->route('buyer.enquiriesAction',['view',$enquiry->id]);
            }

            $categories =Attribute::where('type',0)->where('status','active')->whereNull('parent_id')->orderBy('name')->get(['id','name']);

            return view(buyerTheme().'enquiryForm',compact('categories'));
        }

        // A buyer only works on their own enquiries.
        $enquiry =$this->enquiriesOf($user)->find($id);
        if(!$enquiry){
            Session()->flash('error','This enquiry is not found');
            return redirect()->route('buyer.enquiries');
        }

        if($action=='close' && $r->isMethod('post')){
            $enquiry->status ='closed';
            $enquiry->save();

            Session()->flash('success','The enquiry is closed');
            return redirect()->route('buyer.enquiriesAction',['view',$enquiry->id]);
        }

        if($action=='delete' && $r->isMethod('post')){
            $enquiry->quotes()->delete();
            $enquiry->delete();

            Session()->flash('success','The enquiry is deleted');
            return redirect()->route('buyer.enquiries');
        }

        $quotes =$enquiry->quotes()->with('user:id,name')->latest()->get();
        $enquiry->load('category:id,name');

        return view(buyerTheme().'enquiryView',compact('enquiry','quotes'));
    }

    public function community(Request $r){
        return $this->communityList($r,'buyer',buyerTheme().'community');
    }

    public function communityAction(Request $r,$action,$id=null){
        return $this->communityHandle($r,'buyer',buyerTheme().'community',$action,$id);
    }

    public function messages(Request $r){
        return $this->messagesPage($r,'buyer',buyerTheme().'messages');
    }

    public function messageSend(Request $r){
        return $this->messagesSend($r,'buyer');
    }

    public function messagePoll(Request $r){
        return $this->messagesPoll($r,'buyer');
    }

    public function profilePhoto(Request $r){
        return $this->accountPhoto($r);
    }

    public function profile(Request $r){
        return $this->accountProfile($r,buyerTheme().'profile');
    }

    // The "Reset Password" button lives on the settings page; only its POST is handled here.
    public function changePassword(Request $r){
        if(!$r->isMethod('post')){
            return redirect()->route('buyer.settings');
        }
        return $this->accountRequestPasswordReset($r);
    }

    // A buyer opts in to selling; this only flags the buyer's own account.
    public function becomeSeller(Request $r){

        $user =Auth::user();
        $user->business =true;
        $user->save();

        Session()->flash('success','Your seller account is active. You can post your first ad now');
        return redirect()->route('seller.dashboard');
    }

    // The pages below are shared with the seller dashboard (app/Http/Controllers/Concerns).
    public function addresses(Request $r){
        return $this->sharedAddresses($r,'buyer');
    }

    public function addressAction(Request $r,$action,$id=null){
        return $this->sharedAddressAction($r,'buyer',$action,$id);
    }

    public function paymentMethods(Request $r){
        return $this->sharedPaymentMethods($r,'buyer');
    }

    public function paymentMethodAction(Request $r,$action,$id=null){
        return $this->sharedPaymentMethodAction($r,'buyer',$action,$id);
    }

    public function businessInfo(Request $r){
        return $this->sharedBusinessInfo($r,'buyer');
    }

    public function businessDocumentAction(Request $r,$action,$id=null){
        return $this->sharedBusinessDocumentAction($r,'buyer',$action,$id);
    }

    public function settings(Request $r){
        return $this->sharedSettings($r,'buyer');
    }

    public function settingsToggle(Request $r){
        return $this->sharedSettingsToggle($r);
    }

    public function settingsPreferences(Request $r){
        return $this->sharedSettingsPreferences($r,'buyer');
    }

    public function deactivate(Request $r){
        return $this->sharedDeactivate($r);
    }

    public function marketplace(Request $r){
        return $this->sharedMarketplace($r,'buyer');
    }

    public function marketplaceView(Request $r,$id){
        return $this->sharedMarketplaceView($r,'buyer',$id);
    }

    public function wishlist(Request $request){
        return $this->sharedWishlist($request,'buyer');
    }

    public function wishlistToggle(Request $r,$id){
        return $this->sharedWishlistToggle($r,$id);
    }

    public function savedSearches(Request $r){
        return $this->sharedSavedSearches($r,'buyer');
    }

    public function savedSearchRun(Request $r,$id){
        return $this->sharedSavedSearchRun($r,'buyer',$id);
    }

    public function savedSearchAction(Request $r,$action,$id=null){
        return $this->sharedSavedSearchAction($r,'buyer',$action,$id);
    }

    private function ordersOf($user){
        return Transaction::where('user_id',$user->id)->where('type','0');
    }

    private function enquiryRules(){
        return [
            'title' => 'required|max:191',
            'category' => ['nullable','numeric',Rule::exists('attributes','id')->where(fn ($q) => $q->where('type',0)->where('status','active')->whereNull('parent_id'))],
            'quantity' => 'required|numeric|min:0.01|max:99999999',
            'unit' => 'required|in:'.implode(',',Enquiry::UNITS),
            'description' => 'nullable|max:3000',
        ];
    }

    private function enquiriesOf($user){
        return Enquiry::where('user_id',$user->id);
    }

    private function buyerStats($user){
        return [
            'enquiries' => $this->enquiriesOf($user)->count(),
            'wishlist' => $this->wishlistOf($user)->count(),
            'orders' => $this->ordersOf($user)->count(),
            'completed' => $this->ordersOf($user)->whereIn('status',Transaction::STATUS_GROUPS['Completed'])->count(),
            'spent' => $this->ordersOf($user)->whereIn('status',Transaction::STATUS_GROUPS['Completed'])->sum('amount'),
        ];
    }

    // An order points to the ad (src_id) it was placed for.
    private function adsOfOrders($orders){
        return Post::with(['galleryFiles','user:id,name'])->whereIn('id',$orders->pluck('src_id')->filter())->get()->keyBy('id');
    }
}
