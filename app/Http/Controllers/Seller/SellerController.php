<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Concerns\BuildsDashboard;
use App\Http\Controllers\Concerns\HandlesAccount;
use App\Http\Controllers\Concerns\HandlesAddresses;
use App\Http\Controllers\Concerns\HandlesBusiness;
use App\Http\Controllers\Concerns\HandlesCommunity;
use App\Http\Controllers\Concerns\HandlesMarketplace;
use App\Http\Controllers\Concerns\HandlesMessages;
use App\Http\Controllers\Concerns\HandlesPaymentMethods;
use App\Http\Controllers\Concerns\HandlesSettings;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Enquiry;
use App\Models\EnquiryQuote;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostAttribute;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class SellerController extends Controller
{
    use HandlesAccount, BuildsDashboard, HandlesMessages, HandlesCommunity, HandlesAddresses, HandlesPaymentMethods, HandlesBusiness, HandlesSettings, HandlesMarketplace;

    // The public profile page: banner, ad numbers, business info, verification, activity and reviews.
    public function profileOverview(Request $request){

        $user =Auth::user();

        $listings =$user->myListings()->where('status','<>','temp');

        $statusCounts =(clone $listings)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status');
        $stats =[
            'total' => $statusCounts->sum(),
            'active' => $statusCounts->get('active',0),
            'pending' => $statusCounts->get('pending',0),
            'solded' => $statusCounts->get('solded',0),
            'cancelled' => $statusCounts->get('cancelled',0),
            'views' => (clone $listings)->sum('view'),
            'featured' => (clone $listings)->where('fetured',true)->count(),
        ];

        $recent =(clone $listings)->with(['galleryFiles','category'])->latest()->take(5)->get();
        $payments =Transaction::where('user_id',$user->id)->latest()->take(3)->get();

        // Categories the seller advertises in, with the number of ads.
        $categories =(clone $listings)->whereNotNull('category_id')->selectRaw('category_id, count(*) as total')->groupBy('category_id')
        ->orderByDesc('total')->pluck('total','category_id');
        $categoryNames =Attribute::whereIn('id',$categories->keys())->pluck('name','id');

        $reviews =$this->reviewsOf($user);
        $activity =$this->activityOf($user,'seller',$reviews);
        $summary =$this->profileSummary($user,'seller');

        return view(sellerTheme().'profileOverview',compact('user','summary','stats','recent','payments','categories','categoryNames','reviews','activity'));
    }

    // Seller home: welcome banner, numbers, recent orders, open buyer requirements and what happened lately.
    public function dashboard(Request $request){

        $user =Auth::user();

        $listings =$user->myListings()->where('status','<>','temp');
        $completed =$this->ordersOf($user)->whereIn('status',Transaction::STATUS_GROUPS['Completed']);
        $stats =[
            'ads' => (clone $listings)->count(),
            'active' => (clone $listings)->where('status','active')->count(),
            'orders' => $this->ordersOf($user)->count(),
            'quotes' => EnquiryQuote::where('user_id',$user->id)->count(),
            'earned' => (clone $completed)->sum('amount'),
        ];

        $recentOrders =$this->ordersOf($user)->latest()->take(4)->get();
        $orderAds =$this->adsOfOrders($recentOrders);
        $buyers =$this->buyersOf($recentOrders);
        $requirements =$this->openRequirements($user)->with(['user:id,name','category:id,name'])->withCount('quotes')
            ->with(['quotes' => fn ($q) => $q->where('user_id',$user->id)])->latest()->latest('id')->take(3)->get();

        $reviews =$this->reviewsOf($user);
        // Admin notifications (ad approved / rejected) come before the rest of the activity.
        $notices =$user->notifications()->take(4)->get()->map(fn ($n) => [
            'icon'=>($n->data['status'] ?? '')=='rejected'?'fa-circle-xmark':'fa-circle-check',
            'title'=>$n->data['title'] ?? 'Notification',
            'sub'=>($n->data['reason'] ?? null)?'Reason: '.\Str::limit($n->data['reason'],60):\Str::limit($n->data['ad_name'] ?? '',50),
            'date'=>$n->created_at,
            'unread'=>!$n->read_at,
        ]);
        $activity =$notices->concat($this->activityOf($user,'seller',$reviews))->take(4);
        $summary =$this->profileSummary($user,'seller');

        return view(sellerTheme().'dashboard',compact('user','summary','stats','recentOrders','orderAds','buyers','requirements','activity'));
    }

    // Orders buyers placed on this seller's ads.
    public function orders(Request $request){

        $user =Auth::user();

        // ?status= is one of the Transaction::STATUS_GROUPS names.
        $status =array_key_exists($request->status,Transaction::STATUS_GROUPS)?$request->status:null;

        $perStatus =$this->ordersOf($user)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total','status');
        $counts =['All'=>$perStatus->sum()];
        foreach(Transaction::STATUS_GROUPS as $group=>$statuses){
            $counts[$group] =$perStatus->only($statuses)->sum();
        }

        $orders =$this->ordersOf($user)->when($status,fn ($q) => $q->whereIn('status',Transaction::STATUS_GROUPS[$status]))->latest()->paginate(10)->withQueryString();
        $orderAds =$this->adsOfOrders($orders);
        $buyers =$this->buyersOf($orders);
        $summary =$this->profileSummary($user,'seller');

        return $this->dashboardPage('seller','Orders Received','orders',compact('orders','orderAds','buyers','summary','status','counts'));
    }

    // Requirements buyers posted (enquiries) that this seller can answer with a quote.
    public function enquiries(Request $request){

        $user =Auth::user();

        $categories =Attribute::where('type',0)->where('status','active')->whereNull('parent_id')->orderBy('name')->get(['id','name']);
        $category =$categories->firstWhere('id',(int) $request->category);
        $status =in_array($request->status,['new','quoted'])?$request->status:null;

        $base =$this->openRequirements($user)->when($category,fn ($q) => $q->where('category_id',$category->id));
        $mine =fn ($q) => $q->where('user_id',$user->id);
        $counts =[
            'all' => (clone $base)->count(),
            'new' => (clone $base)->whereDoesntHave('quotes',$mine)->count(),
            'quoted' => (clone $base)->whereHas('quotes',$mine)->count(),
        ];

        $requirements =(clone $base)->with(['user:id,name','category:id,name'])->withCount('quotes')->with(['quotes' => $mine])
            ->when($status=='new',fn ($q) => $q->whereDoesntHave('quotes',$mine))
            ->when($status=='quoted',fn ($q) => $q->whereHas('quotes',$mine))
            ->latest()->latest('id')->paginate(10)->withQueryString();

        return view(sellerTheme().'enquiries',compact('requirements','categories','category','status','counts'));
    }

    // One requirement with the seller's quote form; send, change and withdraw the quote.
    public function enquiriesAction(Request $r,$action,$id=null){

        $user =Auth::user();

        $enquiry =$this->openRequirements($user)->with(['user','category'])->withCount('quotes')->find($id);
        if(!$enquiry){
            Session()->flash('error','This requirement is not available. It may be closed or expired');
            return redirect()->route('seller.enquiries');
        }

        $quote =$enquiry->quotes()->where('user_id',$user->id)->first();

        if($action=='quote' && $r->isMethod('post')){
            $r->validate([
                'price' => 'required|numeric|min:0.01|max:9999999999',
                'message' => 'nullable|max:1000',
            ]);

            $update =(bool) $quote;
            if(!$quote){
                $quote =new EnquiryQuote();
                $quote->enquiry_id =$enquiry->id;
                $quote->user_id =$user->id;
            }
            $quote->price =$r->price;
            $quote->message =trim((string) $r->message)?:null;
            $quote->save();

            Session()->flash('success',$update?'Your quote is updated':'Your quote is sent to the buyer');
            return redirect()->route('seller.enquiriesAction',['view',$enquiry->id]);
        }

        if($action=='withdraw' && $r->isMethod('post')){
            if($quote){
                $quote->delete();
                Session()->flash('success','Your quote is withdrawn');
            }
            return redirect()->route('seller.enquiriesAction',['view',$enquiry->id]);
        }

        $currency =general()->currency ?: '৳';

        return view(sellerTheme().'enquiryView',compact('enquiry','quote','currency'));
    }

    // The pages below are shared with the buyer dashboard (app/Http/Controllers/Concerns).
    public function addresses(Request $r){
        return $this->sharedAddresses($r,'seller');
    }

    public function addressAction(Request $r,$action,$id=null){
        return $this->sharedAddressAction($r,'seller',$action,$id);
    }

    public function paymentMethods(Request $r){
        return $this->sharedPaymentMethods($r,'seller');
    }

    public function paymentMethodAction(Request $r,$action,$id=null){
        return $this->sharedPaymentMethodAction($r,'seller',$action,$id);
    }

    public function businessInfo(Request $r){
        return $this->sharedBusinessInfo($r,'seller');
    }

    public function businessDocumentAction(Request $r,$action,$id=null){
        return $this->sharedBusinessDocumentAction($r,'seller',$action,$id);
    }

    public function settings(Request $r){
        return $this->sharedSettings($r,'seller');
    }

    public function settingsToggle(Request $r){
        return $this->sharedSettingsToggle($r);
    }

    public function settingsPreferences(Request $r){
        return $this->sharedSettingsPreferences($r,'seller');
    }

    public function deactivate(Request $r){
        return $this->sharedDeactivate($r);
    }

    public function marketplace(Request $r){
        return $this->sharedMarketplace($r,'seller');
    }

    public function marketplaceView(Request $r,$id){
        return $this->sharedMarketplaceView($r,'seller',$id);
    }

    public function wishlist(Request $request){
        return $this->sharedWishlist($request,'seller');
    }

    public function wishlistToggle(Request $r,$id){
        return $this->sharedWishlistToggle($r,$id);
    }

    public function savedSearches(Request $r){
        return $this->sharedSavedSearches($r,'seller');
    }

    public function savedSearchRun(Request $r,$id){
        return $this->sharedSavedSearchRun($r,'seller',$id);
    }

    public function savedSearchAction(Request $r,$action,$id=null){
        return $this->sharedSavedSearchAction($r,'seller',$action,$id);
    }

    public function community(Request $r){
        return $this->communityList($r,'seller',sellerTheme().'community');
    }

    public function communityAction(Request $r,$action,$id=null){
        return $this->communityHandle($r,'seller',sellerTheme().'community',$action,$id);
    }

    public function messages(Request $r){
        return $this->messagesPage($r,'seller',sellerTheme().'messages');
    }

    public function messageSend(Request $r){
        return $this->messagesSend($r,'seller');
    }

    public function profilePhoto(Request $r){
        return $this->accountPhoto($r);
    }

    public function profile(Request $r){
        return $this->accountProfile($r,sellerTheme().'profile');
    }

    // The "Reset Password" button lives on the settings page; only its POST is handled here.
    public function changePassword(Request $r){
        if(!$r->isMethod('post')){
            return redirect()->route('seller.settings');
        }
        return $this->accountRequestPasswordReset($r);
    }

    public function listings(Request $r){
        $user = Auth::user();
        $posts =$user->myListings()->with('galleryFiles')->latest()->where('status','<>','temp')->paginate(10);

        return view(sellerTheme().'listings',compact('posts'));
    }

    public function listingsAction(Request $r,$action,$id=null){

        $user =Auth::user();

        if($action=='create'){
            $post =Post::where('type',3)->where('status','temp')->where('addedby_id',Auth::id())->first();
            if(!$post){
                $post =new Post();
                $post->type =3;
                $post->status ='temp';
                $post->addedby_id =Auth::id();
            }
            $post->created_at =Carbon::now();
            $post->save();

            return redirect()->route('seller.listingsAction',['edit',$post->id]);
        }

        // A seller only works on their own ads.
        $post =$user->myListings()->find($id);
        if(!$post){
            Session()->flash('error','This Ad Are Not Found');
            return redirect()->route('seller.listings');
        }

        if($action=='update'){

            $check = $r->validate([
                'category' => 'required|numeric',
                'title' => 'required|max:191',
                'price' => 'required|numeric',
                'mobile' => 'required|max:20',
                'description' => 'required|max:5000',
                'district' => 'required|numeric',
                'thana' => 'required|numeric',
                'subcategories' => 'nullable|array',
                'subcategories.*' => 'numeric',
            ]);

            if(!$post->galleryFiles()->exists()){
                return back()->withInput()->withErrors(['photos'=>'Please add a main photo of the product.']);
            }

            $post->name =$r->title;
            $post->category_id =$r->category;
            $post->district_id =$r->district;
            $post->thana_id =$r->thana;
            $post->description =$r->description;
            $post->phone_number =$r->mobile;
            $post->negotiable =$r->negotiable?true:false;
            $post->hide_phone =$r->hide_phone?true:false;
            $post->price =$r->price?:0;
            if($post->status=='temp'){
            $post->status ='pending';
            Session()->flash('success','Your Ad Are successfully created. wait for approval');
            }elseif($post->status=='cancelled'){
            // A rejected ad that was fixed goes back to the admin for review.
            $post->status ='pending';
            $post->reject_reason =null;
            Session()->flash('success','Your ad was sent for approval again.');
            }else{
            Session()->flash('success','Your Ad Are successfully updated.');
            }

            $slug =Str::slug($post->name);
            if($slug==null){
            $post->slug=$post->id;
            }else{
            if(Post::where('type',3)->where('slug',$slug)->whereNotIn('id',[$post->id])->count() >0){
            $post->slug=$slug.'-'.$post->id;
            }else{
            $post->slug=$slug;
            }
            }

            // Only sub categories of the chosen category are accepted. The first one is kept in
            // posts.sub_category_id, all of them are linked in post_attributes (type 0).
            $subIds =Attribute::where('type',0)->where('status','active')->where('parent_id',$r->category)
            ->whereIn('id',(array) $r->subcategories)->orderBy('name')->pluck('id')->all();
            $post->sub_category_id =$subIds[0] ?? null;

            $post->save();

            // Existing links to anything but sub categories are left as they are.
            $allSubIds =Attribute::where('type',0)->whereNotNull('parent_id')->pluck('id');
            $post->serviceCtgs()->whereIn('reff_id',$allSubIds)->whereNotIn('reff_id',$subIds)->delete();
            foreach($subIds as $i=>$subId){
                $link =$post->serviceCtgs()->where('reff_id',$subId)->first();
                if(!$link){
                    $link =new PostAttribute();
                    $link->src_id =$post->id;
                    $link->reff_id =$subId;
                    $link->type =0;
                }
                $link->drag =$i;
                $link->addedby_id =Auth::id();
                $link->save();
            }

            return redirect()->route('seller.listings');

        }
        if($action=='upload-image'){

                // ?main=1 comes from the "Main photo" button: one photo that replaces the current main photo.
                $asMain =$r->boolean('main');
                $oldMain =$asMain ? $post->galleryFiles()->first() : null;

                $maxPhotos =10;
                $room =$asMain ? 1 : $maxPhotos-$post->galleryFiles()->count();
                if($room<1){
                    return Response()->json(['message'=>'An ad can have at most '.$maxPhotos.' photos. Delete one to add another.'],422);
                }

                $r->validate([
                    'images' => 'required|array|max:'.$room,
                    'images.*' => 'file|mimetypes:image/*|max:20480',
                ],[
                    'images.required' => 'The photo did not reach the server. It may be too big; please try a smaller photo.',
                    'images.*.uploaded' => 'The photo did not reach the server. It may be too big; please try a smaller photo.',
                    'images.*.mimetypes' => 'Only photos can be uploaded.',
                    'images.*.max' => 'A photo can be at most 20 MB.',
                    'images.max' => 'You can add '.$room.' more '.Str::plural('photo',$room).' (at most '.$maxPhotos.' per ad).',
                ]);

                // Every photo is compressed to a WebP of at most 80 KB (see uploadCompressedImage).
                // More photos go after the others; a main photo goes first and the old main photo is removed
                // (it does not drop into "More photos").
                $position =$post->nextPhotoPosition();
                $uploaded =null;
                foreach($r->file('images') as $file){
                    $uploaded =uploadCompressedImage($file,$post->id,1,3,Auth::id(),false);
                    $uploaded->drag =$position++;
                    $uploaded->save();
                }
                if($asMain && $uploaded){
                    if($oldMain){
                        if(File::exists($oldMain->file_url)){
                            File::delete($oldMain->file_url);
                        }
                        $oldMain->delete();
                    }
                    $post->setMainPhoto($uploaded->id);
                }

            $view =View(sellerTheme().'uploadImages',compact('post'))->render();
            return Response()->json([
              'success' => true,
              'view' => $view,
            ]);
        }

        if($action=='main-image'){
            $post->setMainPhoto($r->image_id);
            $view =View(sellerTheme().'uploadImages',compact('post'))->render();
            return Response()->json([
              'success' => true,
              'view' => $view,
            ]);
        }

        if($action=='delete-image'){
            $image =$post->galleryFiles()->find($r->image_id);
            if($image){
                if(File::exists($image->file_url)){
                    File::delete($image->file_url);
                }
                $image->delete();
            }
            $view =View(sellerTheme().'uploadImages',compact('post'))->render();
            return Response()->json([
              'success' => true,
              'view' => $view,
            ]);
        }

        // Same clean-up as the admin panel: images, category/tag links, then the ad itself.
        if($action=='delete' && $r->isMethod('post')){

            $medias =Media::latest()->where('src_type',1)->where('src_id',$post->id)->get();
            foreach($medias as $media){
              if(File::exists($media->file_url)){
                File::delete($media->file_url);
              }
              $media->delete();
            }

            $post->serviceCtgs()->delete();
            $post->postTags()->delete();
            $post->delete();

            Session()->flash('success','Your Ad Are Successfully Deleted');
            return redirect()->route('seller.listings');
        }

        $categories =Attribute::where('type',0)->where('status','active')->where('parent_id',null)->select(['id','name','parent_id'])->get();

        $subCategories =Attribute::where('type',0)->where('status','active')->whereNotNull('parent_id')
        ->orderBy('name')->select(['id','name','parent_id'])->get()->groupBy('parent_id');
        $selectedSubs =$post->serviceCtgs()->pluck('reff_id')->all();

        return view(sellerTheme().'listingsEdit',compact('post','categories','subCategories','selectedSubs'));
    }

    // Messages from the admin (ad approved / rejected with the reason). Opening the page marks them read.
    public function notifications(Request $r){
        $user =Auth::user();
        $notifications =$user->notifications()->paginate(15);
        $unreadIds =$notifications->getCollection()->whereNull('read_at')->pluck('id');

        if($unreadIds->isNotEmpty()){
            $user->unreadNotifications()->whereIn('id',$unreadIds)->update(['read_at'=>now()]);
        }

        return view(sellerTheme().'notifications',compact('notifications','unreadIds'));
    }

    // Removes every notification of the seller.
    public function notificationsClear(){
        Auth::user()->notifications()->delete();
        Session()->flash('success','All notifications cleared');
        return redirect()->route('seller.notifications');
    }

    // Removes one notification (only the seller's own).
    public function notificationDelete($id){
        Auth::user()->notifications()->where('id',$id)->delete();
        Session()->flash('success','Notification removed');
        return back();
    }

    public function payments(Request $r){

        $payments =Transaction::where('user_id',Auth::id())->latest()->paginate(10);

        return view(sellerTheme().'payments',compact('payments'));
    }

    // Orders placed on the ads of the seller (a payment of type 0 points to the ad with src_id).
    private function ordersOf($user){
        return Transaction::where('type','0')->whereIn('src_id',$user->myListings()->select('id'));
    }

    private function adsOfOrders($orders){
        return Post::with(['galleryFiles','user:id,name'])->whereIn('id',$orders->pluck('src_id')->filter())->get()->keyBy('id');
    }

    private function buyersOf($orders){
        return User::whereIn('id',$orders->pluck('user_id')->filter())->get(['id','name'])->keyBy('id');
    }

    // Requirements of other users that the admin approved and that still take quotes.
    private function openRequirements($user){
        return Enquiry::published()->where('user_id','<>',$user->id)->whereHas('user',fn ($q) => $q->notDeactivated());
    }
}
