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

// Addresses page and actions shared by the buyer and seller dashboards. $role is buyer or seller.
trait HandlesAddresses
{
    protected function sharedAddresses(Request $r,$role){

        $user =Auth::user();

        $addresses =Address::with('district')->where('user_id',$user->id)->orderByDesc('is_default')->orderByDesc('id')->get();
        $districts =Country::where('type',3)->orderBy('name')->get(['id','name']);

        return $this->dashboardPage($role,'Addresses','addresses',compact('user','addresses','districts'));
    }

    // Add / edit (save), make default, and remove an address.
    protected function sharedAddressAction(Request $r,$role,$action,$id=null){

        $user =Auth::user();
        $mine =Address::where('user_id',$user->id);

        if($action=='save'){
            $r->validate([
                'label' => 'required|max:60',
                'address' => 'required|max:300',
                'phone' => 'nullable|max:30',
                'district' => ['nullable',Rule::exists('countries','id')->where('type',3)],
            ]);

            $address =null;
            if($r->address_id){
                $address =(clone $mine)->find($r->address_id);
                if(!$address){
                    Session()->flash('error','This address is not found');
                    return redirect()->route($role.'.addresses');
                }
            }else{
                $count =(clone $mine)->count();
                if($count>=Address::LIMIT){
                    Session()->flash('error','You can keep '.Address::LIMIT.' addresses at most. Remove one to add a new address');
                    return redirect()->route($role.'.addresses');
                }
                $address =new Address();
                $address->user_id =$user->id;
                // The first address is the default one.
                $address->is_default =$count==0;
            }

            $address->label =$r->label;
            $address->address =$r->address;
            $address->phone =$r->phone;
            $address->district_id =$r->district?:null;
            $address->save();

            Session()->flash('success',$r->address_id?'Your address is updated':'Your address is saved');
            return redirect()->route($role.'.addresses');
        }

        $address =(clone $mine)->find($id);
        if(!$address || !$r->isMethod('post')){
            if(!$address){
                Session()->flash('error','This address is not found');
            }
            return redirect()->route($role.'.addresses');
        }

        if($action=='default'){
            (clone $mine)->update(['is_default'=>false]);
            $address->is_default =true;
            $address->save();

            Session()->flash('success','Default address is changed');
        }

        if($action=='delete'){
            $wasDefault =$address->is_default;
            $address->delete();

            // Keep one default while there are addresses.
            if($wasDefault && ($next =(clone $mine)->latest('id')->first())){
                $next->is_default =true;
                $next->save();
            }

            Session()->flash('success','The address is removed');
        }

        return redirect()->route($role.'.addresses');
    }
}
