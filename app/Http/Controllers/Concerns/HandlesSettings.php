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

// Settings page (notifications, privacy, language, deactivate) shared by the buyer and seller dashboards. $role is buyer or seller.
trait HandlesSettings
{
    protected function sharedSettings(Request $r,$role){

        $user =Auth::user();
        $setting =UserSetting::forUser($user->id);

        return $this->dashboardPage($role,$role=='expert'?'Account Settings':'Settings','settings',compact('user','setting'));
    }

    // One notification / privacy switch, saved as soon as it is moved (ajax).
    protected function sharedSettingsToggle(Request $r){

        $r->validate([
            'key' => ['required',Rule::in(UserSetting::toggleKeys())],
            'value' => 'required|boolean',
        ]);

        $setting =UserSetting::forUser(Auth::id());
        $key =$r->key;
        $setting->$key =$r->boolean('value');
        $setting->save();

        return response()->json(['success'=>true]);
    }

    protected function sharedSettingsPreferences(Request $r,$role){

        $r->validate([
            'language' => ['required',Rule::in(array_keys(UserSetting::LANGUAGES))],
            // An expert only chooses the language.
            'currency' => [$role=='expert'?'nullable':'required',Rule::in(array_keys(UserSetting::CURRENCIES))],
        ]);

        $setting =UserSetting::forUser(Auth::id());
        $setting->language =$r->language;
        if($r->filled('currency')){
            $setting->currency =$r->currency;
        }
        $setting->save();

        // The site reads the language from the session (see the /switch route).
        Session::put('lang',$r->language);

        Session()->flash('success','Your preferences are saved');
        return redirect()->route($role.'.settings');
    }

    // Hides the user's ads, posts and comments and logs out; the next login turns the account on again.
    protected function sharedDeactivate(Request $r){

        $user =Auth::user();

        $r->validate(['deactivate_password' => 'required|string']);
        if(!Hash::check($r->deactivate_password,$user->password)){
            return back()->withErrors(['deactivate_password'=>'The password is not correct.']);
        }

        $user->deactivated_at =now();
        $user->save();

        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        Session()->flash('error','Your account is deactivated. Log in again any time to turn it back on.');
        return redirect()->route('login');
    }
}
