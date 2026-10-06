<?php

namespace App\Http\Controllers\Concerns;

use Auth;
use App\Models\Address;
use App\Models\Attribute;
use App\Models\BusinessDocument;
use App\Models\BusinessProfile;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\SavedSearch;
use App\Models\UserSetting;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// Marketplace, ad page, wishlist and saved searches shared by the buyer and seller dashboards. $role is buyer or seller.
trait HandlesMarketplace
{
    // The live ads of all sellers. The topbar search and the saved searches land here too:
    // ?search= (keyword), category, district, min_price and max_price narrow the list down.
    protected function sharedMarketplace(Request $r,$role){

        $categories =Attribute::where('type',0)->where('status','active')->whereNull('parent_id')->orderBy('name')->get(['id','name']);
        $category =$categories->firstWhere('id',(int) $r->category);
        $keyword =trim((string) $r->search);
        $district =is_numeric($r->district)?Country::where('type',3)->find($r->district):null;
        $min =is_numeric($r->min_price)?$r->min_price:null;
        $max =is_numeric($r->max_price)?$r->max_price:null;

        $ads =Post::with(['galleryFiles','user:id,name','district:id,name','thana:id,name'])->live()
            ->withAvg(['postComments as avg_rating'],'rating')
            ->when($keyword!=='',fn ($q) => $q->keyword($keyword))
            ->filteredBy($category?->id,$district?->id,$min,$max)
            ->latest()->latest('id')->paginate(12)->withQueryString();

        // Sellers on this page with a verified trade licence get the Verified badge.
        $verifiedSellers =BusinessDocument::where('type',BusinessDocument::TRADE_LICENSE)->where('status','verified')
            ->whereIn('user_id',$ads->pluck('addedby_id')->filter()->unique())->pluck('user_id')->flip();

        return $this->dashboardPage($role,'Marketplace','marketplace',compact('ads','categories','category','keyword','district','min','max','verifiedSellers'));
    }

    // One ad inside the dashboard: photos, price, seller, and the actions of the viewer on it.
    protected function sharedMarketplaceView(Request $r,$role,$id){

        $user =Auth::user();

        $ad =Post::with(['galleryFiles','user'])->live()->find($id);
        if(!$ad){
            Session()->flash('error','This ad is not available');
            return redirect()->route($role.'.marketplace');
        }

        $saved =Wishlist::has($user->id,$ad->id);
        $related =Post::with(['galleryFiles','user:id,name'])->live()->where('id','<>',$ad->id)
            ->when($ad->category_id,fn ($q) => $q->where('category_id',$ad->category_id))->latest()->latest('id')->take(4)->get();

        return $this->dashboardPage($role,$ad->name,'marketplace-view',compact('ad','saved','related'));
    }

    protected function sharedWishlist(Request $request,$role){

        $items =$this->wishlistOf(Auth::user())->with(['post.user:id,name','post.galleryFiles'])->latest()->paginate(12);

        return $this->dashboardPage($role,'My Wishlist','wishlist',compact('items'));
    }

    // Saves the ad, or takes it out of the wishlist when it is already there.
    protected function sharedWishlistToggle(Request $r,$id){

        $user =Auth::user();

        $saved =Wishlist::where('user_id',$user->id)->where('post_id',$id)->first();
        if($saved){
            $saved->delete();
            Session()->flash('success','Removed from your wishlist');
            return back();
        }

        $ad =Post::where('type',3)->where('status','active')->find($id);
        if(!$ad){
            Session()->flash('error','This ad is not found');
            return back();
        }

        $saved =new Wishlist();
        $saved->user_id =$user->id;
        $saved->post_id =$ad->id;
        $saved->save();

        Session()->flash('success','Saved to your wishlist');
        return back();
    }

    // Only ads that are still live are listed and counted.
    protected function wishlistOf($user){
        return Wishlist::where('user_id',$user->id)->whereHas('post',fn ($q) => $q->where('type',3)->where('status','active'));
    }

    protected function sharedSavedSearches(Request $r,$role){

        $user =Auth::user();

        $searches =SavedSearch::with(['category','district'])->where('user_id',$user->id)->latest('id')->get();
        // Ads posted since a search was last opened (only counted while its alerts are on).
        $newCounts =$searches->mapWithKeys(fn ($s) => [$s->id => $s->alerts ? $s->newCount() : 0]);
        $categories =Attribute::where('type',0)->where('status','active')->whereNull('parent_id')->orderBy('name')->get(['id','name']);
        $districts =Country::where('type',3)->orderBy('name')->get(['id','name']);
        $currency =general()->currency ?: '৳';

        return $this->dashboardPage($role,'Saved Searches','saved-searches',compact('searches','newCounts','categories','districts','currency'));
    }

    // Opens the search in the marketplace of the dashboard and marks its ads as seen.
    protected function sharedSavedSearchRun(Request $r,$role,$id){

        $search =SavedSearch::where('user_id',Auth::id())->find($id);
        if(!$search){
            Session()->flash('error','This saved search is not found');
            return redirect()->route($role.'.savedSearches');
        }

        $search->checked_at =now();
        $search->save();

        return redirect()->route($role.'.marketplace',$search->searchParams());
    }

    // Save a search (from this page or the site search), switch its alerts, delete it.
    protected function sharedSavedSearchAction(Request $r,$role,$action,$id=null){

        $user =Auth::user();
        $mine =SavedSearch::where('user_id',$user->id);
        $currency =general()->currency ?: '৳';

        if($action=='save'){
            $r->validate([
                'keyword' => 'required|max:120',
                'name' => 'nullable|max:120',
                'category' => ['nullable',Rule::exists('attributes','id')->where(fn ($q) => $q->where('type',0)->where('status','active')->whereNull('parent_id'))],
                'district' => ['nullable',Rule::exists('countries','id')->where('type',3)],
                'min_price' => 'nullable|numeric|min:0|max:9999999999',
                'max_price' => ['nullable','numeric','min:0','max:9999999999',$r->filled('min_price')?'gte:min_price':'nullable'],
            ]);

            $keyword =trim($r->keyword);
            $category =$r->category?:null;
            $district =$r->district?:null;
            $min =$r->filled('min_price')?$r->min_price:null;
            $max =$r->filled('max_price')?$r->max_price:null;

            $same =(clone $mine)->where('keyword',$keyword)->where('category_id',$category)->where('district_id',$district)
                ->where('min_price',$min)->where('max_price',$max)->exists();
            if($same){
                Session()->flash('info','You have already saved this search');
                return redirect()->route($role.'.savedSearches');
            }
            if((clone $mine)->count()>=SavedSearch::LIMIT){
                Session()->flash('error','You can keep '.SavedSearch::LIMIT.' saved searches at most. Delete one to save a new search');
                return redirect()->route($role.'.savedSearches');
            }

            $search =new SavedSearch();
            $search->user_id =$user->id;
            $search->keyword =$keyword;
            $search->name =trim((string) $r->name)?:SavedSearch::defaultName($keyword,$min,$max,$currency);
            $search->category_id =$category;
            $search->district_id =$district;
            $search->min_price =$min;
            $search->max_price =$max;
            $search->alerts =true;
            $search->checked_at =now();
            $search->save();

            Session()->flash('success','Your search is saved');
            return redirect()->route($role.'.savedSearches');
        }

        $search =(clone $mine)->find($id);
        if(!$search || !$r->isMethod('post')){
            if($r->expectsJson()){
                return response()->json(['success'=>false],404);
            }
            if(!$search){
                Session()->flash('error','This saved search is not found');
            }
            return redirect()->route($role.'.savedSearches');
        }

        if($action=='alerts'){
            $r->validate(['value' => 'required|boolean']);
            $search->alerts =$r->boolean('value');
            // Turning alerts on starts counting new ads from now.
            if($search->alerts){
                $search->checked_at =now();
            }
            $search->save();

            return response()->json(['success'=>true]);
        }

        if($action=='delete'){
            $search->delete();

            Session()->flash('success','The saved search is deleted');
        }

        return redirect()->route($role.'.savedSearches');
    }
}
