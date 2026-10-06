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

// Payment methods page and actions shared by the buyer and seller dashboards. $role is buyer or seller.
trait HandlesPaymentMethods
{
    // Payment methods: the list, the add / edit form (?edit=ID) and its POST.
    protected function sharedPaymentMethods(Request $r,$role){

        $user =Auth::user();
        $mine =PaymentMethod::where('user_id',$user->id);

        if($r->isMethod('post')){

            $isBank =$r->type==PaymentMethod::BANK;
            // Spaces and dashes are typed freely; +88 / 88 in front of a mobile number is dropped.
            $number =preg_replace('/[\s\-]/','',(string) $r->account_number);
            if(!$isBank){
                $number =preg_replace('/^(\+?88)(?=01)/','',$number);
            }
            $r->merge(['account_number'=>$number]);

            $r->validate([
                'type' => ['required',Rule::in(PaymentMethod::TYPES)],
                'account_number' => ['required',$isBank?'regex:/^[A-Za-z0-9]{6,30}$/':'regex:/^01[3-9][0-9]{8}$/'],
                'account_kind' => ['nullable',Rule::in(PaymentMethod::KINDS)],
                'bank_name' => [$isBank?'required':'nullable','max:80'],
                'branch' => 'nullable|max:80',
                'payment_id' => 'nullable|integer',
            ],[
                'account_number.regex' => $isBank?'Enter a valid account number (6 to 30 letters or digits).':'Enter a valid mobile number like 017XXXXXXXX.',
            ]);

            $method =null;
            if($r->payment_id){
                $method =(clone $mine)->find($r->payment_id);
                if(!$method){
                    Session()->flash('error','This payment method is not found');
                    return redirect()->route($role.'.paymentMethods');
                }
            }else{
                $count =(clone $mine)->count();
                if($count>=PaymentMethod::LIMIT){
                    Session()->flash('error','You can keep '.PaymentMethod::LIMIT.' payment methods at most. Remove one to add a new one');
                    return redirect()->route($role.'.paymentMethods');
                }
                $method =new PaymentMethod();
                $method->user_id =$user->id;
                // The first method is the default one.
                $method->is_default =$count==0;
            }

            $duplicate =(clone $mine)->where('type',$r->type)->where('account_number',$number)->where('id','<>',$method->id ?: 0)->exists();
            if($duplicate){
                return back()->withInput()->withErrors(['account_number'=>'You have already added this account.']);
            }

            $method->type =$r->type;
            $method->account_number =$number;
            $method->account_kind =$isBank?null:($r->account_kind?:'Personal');
            $method->bank_name =$isBank?$r->bank_name:null;
            $method->branch =$isBank?$r->branch:null;
            $method->save();

            Session()->flash('success',$r->payment_id?'Your payment method is updated':'Your payment method is added');
            return redirect()->route($role.'.paymentMethods');
        }

        $methods =(clone $mine)->orderByDesc('is_default')->orderByDesc('id')->get();
        $editing =$r->query('edit')?$methods->firstWhere('id',(int) $r->query('edit')):null;

        return $this->dashboardPage($role,'Payment Methods','payment-methods',compact('methods','editing'));
    }

    protected function sharedPaymentMethodAction(Request $r,$role,$action,$id=null){

        $user =Auth::user();
        $mine =PaymentMethod::where('user_id',$user->id);

        $method =(clone $mine)->find($id);
        if(!$method){
            Session()->flash('error','This payment method is not found');
            return redirect()->route($role.'.paymentMethods');
        }

        if($action=='default'){
            (clone $mine)->update(['is_default'=>false]);
            $method->is_default =true;
            $method->save();

            Session()->flash('success','Default payment method is changed');
        }

        if($action=='delete'){
            $wasDefault =$method->is_default;
            $method->delete();

            // Keep one default while there are methods.
            if($wasDefault && ($next =(clone $mine)->latest('id')->first())){
                $next->is_default =true;
                $next->save();
            }

            Session()->flash('success','The payment method is removed');
        }

        return redirect()->route($role.'.paymentMethods');
    }
}
