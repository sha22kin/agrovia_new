<?php

namespace App\Http\Controllers\Concerns;

use App\Models\BusinessDocument;
use App\Models\BusinessProfile;
use App\Models\Country;
use App\Models\Review;
use App\Models\Transaction;

// Data shared by the buyer and seller dashboards (profile banner, verification, activity, reviews).
trait BuildsDashboard
{
    protected function profileSummary($user, $role)
    {
        $business =BusinessProfile::where('user_id',$user->id)->first();
        // NID and business (trade license) checks come from the uploaded papers.
        $nid =BusinessDocument::stateOf($user->id,BusinessDocument::NID);
        $license =BusinessDocument::stateOf($user->id,BusinessDocument::TRADE_LICENSE);

        $district =$user->districtN;
        $city =$user->cityN;

        $areas =collect([$district?->name,$city?->name]);
        if($role=='seller'){
            $ids =$user->myListings()->where('status','<>','temp')->whereNotNull('district_id')->distinct()->pluck('district_id');
            $areas =$areas->merge(Country::whereIn('id',$ids)->orderBy('name')->pluck('name'));
        }

        return [
            'role' => $role,
            'publicId' => ($role=='seller'?'SEL-':'BUY-').str_pad($user->id,5,'0',STR_PAD_LEFT),
            'location' => collect([$district?->name ?: $city?->name,'Bangladesh'])->filter()->implode(', '),
            'verified' => (bool) ($user->email_verified_at || $user->verify_code_status),
            // [label, verified?, text when not verified]
            'verification' => [
                ['Email Verified',(bool) $user->email_verified_at,'Email not verified'],
                ['Phone Verified',(bool) $user->verify_code_status,'Phone not verified'],
                ['NID Verified',$nid=='verified',$nid?'NID under review':'NID not submitted'],
                ['Business Verified',$license=='verified',$license?'Business under review':'Business not verified'],
            ],
            'areas' => $business?->areas ?: $areas->filter()->unique()->implode(', '),
            'businessType' => $business?->business_type ?: ($user->company_name ?: ($role=='seller'?'Individual Seller':'Individual Buyer')),
            'businessVerified' => $license=='verified',
            'landArea' => $business?->landLabel(),
            'mainCrops' => $business?->main_crops,
            'currency' => general()->currency ?: '৳',
        ];
    }

    // Reviews and comments the user wrote.
    protected function reviewsOf($user)
    {
        return Review::with('post:id,name,slug')->where('addedby_id',$user->id)->where('status','<>','temp')->latest()->take(20)->get();
    }

    // Latest things the user did: payments, reviews/comments and (sellers) ads.
    protected function activityOf($user, $role, $reviews)
    {
        $items =collect();

        foreach(Transaction::where('user_id',$user->id)->latest()->take(10)->get() as $t){
            $kind =['0'=>'Order','1'=>'Recharge','2'=>'Refund'][$t->type] ?? 'Payment';
            $items->push(['icon'=>'fa-receipt','title'=>$kind.' payment','sub'=>ucfirst($t->status).' · '.general()->currency.' '.number_format($t->amount),'date'=>$t->created_at]);
        }
        foreach($reviews as $r){
            $items->push(['icon'=>'fa-comment-dots','title'=>($r->type==0?'Reviewed ':'Commented on ').($r->post?->name ?: 'a post'),'sub'=>\Str::limit(strip_tags($r->content),70),'date'=>$r->created_at]);
        }
        if($role=='seller'){
            foreach($user->myListings()->where('status','<>','temp')->latest()->take(10)->get() as $ad){
                $items->push(['icon'=>'fa-rectangle-ad','title'=>'Posted "'.\Str::limit($ad->name,45).'"','sub'=>ucfirst($ad->status),'date'=>$ad->created_at]);
            }
        }

        return $items->filter(fn ($i) => $i['date'])->sortByDesc('date')->take(10)->values();
    }
}
