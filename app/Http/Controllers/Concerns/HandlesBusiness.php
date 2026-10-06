<?php

namespace App\Http\Controllers\Concerns;

use Auth;
use App\Models\Address;
use App\Models\Attribute;
use App\Models\BusinessDocument;
use App\Models\BusinessProfile;
use App\Models\BuyerPreference;
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

// Business information and verification documents shared by the buyer and seller dashboards. $role is buyer or seller.
trait HandlesBusiness
{
    // Company details and the papers uploaded for verification.
    protected function sharedBusinessInfo(Request $r,$role){

        $user =Auth::user();
        $business =BusinessProfile::where('user_id',$user->id)->first() ?: new BusinessProfile();

        if($r->isMethod('post')){
            $r->validate([
                'company_name' => 'nullable|max:200',
                'business_type' => ['nullable',Rule::in(BusinessProfile::TYPES)],
                'license_no' => 'nullable|max:60',
                'tin_bin' => 'nullable|max:60',
                'established_year' => 'nullable|integer|min:1900|max:'.date('Y'),
                'employees' => ['nullable',Rule::in(BusinessProfile::EMPLOYEES)],
                'address' => 'nullable|max:191',
                'areas' => 'nullable|max:300',
                'description' => 'nullable|max:500',
            ]);

            $user->company_name =$r->company_name;
            $user->profile =$r->description;
            $user->save();

            $business->user_id =$user->id;
            $business->business_type =$r->business_type?:null;
            $business->license_no =$r->license_no;
            $business->tin_bin =$r->tin_bin;
            $business->established_year =$r->established_year?:null;
            $business->employees =$r->employees?:null;
            $business->address =$r->address;
            $business->areas =BuyerPreference::cleanInterests($r->areas);
            $business->save();

            Session()->flash('success','Your business information is saved');
            return redirect()->route($role.'.businessInfo');
        }

        $documents =BusinessDocument::where('user_id',$user->id)->oldest()->get();
        $summary =$this->profileSummary($user,$role);

        return $this->dashboardPage($role,'Business Information','business-info',compact('user','business','documents','summary'));
    }

    // Upload, download and delete of the verification papers. The files live on the private disk.
    protected function sharedBusinessDocumentAction(Request $r,$role,$action,$id=null){

        $user =Auth::user();

        if($action=='upload' && $r->isMethod('post')){
            $r->validate([
                'type' => 'required|in:'.implode(',',BusinessDocument::TYPES),
                'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

            // A named paper is kept once: a new upload replaces the old one, unless it is verified already.
            $old =collect();
            if($r->type!=BusinessDocument::OTHER){
                $old =BusinessDocument::where('user_id',$user->id)->where('type',$r->type)->get();
                if($old->contains(fn ($d) => $d->isVerified())){
                    Session()->flash('error','This document is already verified');
                    return redirect()->route($role.'.businessInfo');
                }
            }

            $file =$r->file('document');
            $document =new BusinessDocument();
            $document->user_id =$user->id;
            $document->type =$r->type;
            $document->file_path =$file->store('business-documents/'.$user->id);
            $document->original_name =\Str::limit($file->getClientOriginalName(),180,'');
            $document->status ='pending';
            $document->save();

            foreach($old as $doc){
                Storage::delete($doc->file_path);
                $doc->delete();
            }

            Session()->flash('success','Your document is uploaded. We will review it soon');
            return redirect()->route($role.'.businessInfo');
        }

        // Only the owner opens or removes a paper.
        $document =BusinessDocument::where('user_id',$user->id)->find($id);
        if(!$document){
            Session()->flash('error','This document is not found');
            return redirect()->route($role.'.businessInfo');
        }

        if($action=='download'){
            abort_unless(Storage::exists($document->file_path),404);
            return Storage::download($document->file_path,$document->original_name?:basename($document->file_path));
        }

        if($action=='delete' && $r->isMethod('post')){
            if($document->isVerified()){
                Session()->flash('error','A verified document cannot be deleted');
                return redirect()->route($role.'.businessInfo');
            }
            Storage::delete($document->file_path);
            $document->delete();

            Session()->flash('success','The document is deleted');
            return redirect()->route($role.'.businessInfo');
        }

        return redirect()->route($role.'.businessInfo');
    }
}
