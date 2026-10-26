<?php

namespace App\Http\Controllers\Concerns;

use Auth;
use Hash;
use Session;
use Illuminate\Http\Request;

// Profile and password pages shared by the buyer and seller dashboards.
// Each dashboard passes the view of its own theme.
trait HandlesAccount
{
    protected function accountProfile(Request $r, $view){

        $user =Auth::user();

        if($r->isMethod('post')){

            $check = $r->validate([
                'name' => 'required|max:50',
                'gender' => 'required|in:Male,Female,Other',
                'email' => 'required|email|max:100|unique:users,email,'.$user->id,
                'mobile' => 'nullable|max:20|unique:users,mobile,'.$user->id,
                'address' => 'nullable|max:191',
                'division' => 'nullable|numeric',
                'district' => 'nullable|numeric',
                'city' => 'nullable|numeric',
                'postal_code' => 'nullable|max:20',
                'profile' => 'nullable|max:500',
                'company_name' => 'nullable|max:200',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                // seller farm details
                'land_area' => 'nullable|numeric|min:0|max:99999999',
                'land_unit' => 'nullable|in:'.implode(',',\App\Models\BusinessProfile::LAND_UNITS),
                'main_crops' => 'nullable|max:300',
            ]);

            if($user->isSeller()){
                $business =\App\Models\BusinessProfile::firstOrNew(['user_id'=>$user->id]);
                $business->land_area =$r->filled('land_area') ? $r->land_area : null;
                $business->land_unit =$r->filled('land_area') ? ($r->land_unit ?: 'Bigha') : null;
                $business->main_crops =\App\Models\BuyerPreference::cleanInterests($r->main_crops);
                $business->save();
            }

            $user->name =$r->name;
            $user->gender =$r->gender;
            $user->email =$r->email;
            $user->mobile =$r->mobile;
            $user->district =$r->district;
            $user->city =$r->city;
            $user->postal_code =$r->postal_code;
            $user->profile =$r->profile;
            $user->company_name =$r->company_name;
            $user->address_line1 =$r->address;

            ///////Image Uploard Start////////////
            if($r->hasFile('image')){
                $file =$r->image;
                $src  =$user->id;
                $srcType  =6;
                $fileUse  =1;
                $author=Auth::id();
                uploadImageAsWebp($file,$src,$srcType,$fileUse,$author,true,600);
            }
            ///////Image Upload End////////////

            $user->save();

            Session::flash('success','Your profile is updated');
            return redirect()->back();
        }

        return view($view,compact('user'));
    }

    // The cropped profile photo (ajax): stored as a 600x600 WebP in the media table.
    protected function accountPhoto(Request $r){

        $user =Auth::user();

        $r->validate(['photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120']);

        uploadImageAsWebp($r->file('photo'),$user->id,6,1,Auth::id(),true,600);

        $user->load('imageFile');

        return response()->json([
            'success' => true,
            'message' => 'Your profile photo is updated',
            'url' => route('imageView2',['profile',$user->imageName(),'w'=>120,'h'=>120]),
            'url_small' => route('imageView2',['profile',$user->imageName(),'w'=>80,'h'=>80]),
        ]);
    }

    // "Reset Password" on the settings page: emails this account a 6-digit code, signs them out, and drops
    // them straight into the same OTP-verify -> new-password pages "Forgot password?" uses on the login
    // screen (AuthController::resetPassword()/resetPasswordCheck()/newPassword()) - skipping the "which
    // account" step there, since we already know. Replaces the old current/new/confirm-password form:
    // proving you can still receive mail at the account is a stronger check than typing the old password.
    protected function accountRequestPasswordReset(Request $r){

        $user = Auth::user();

        if(!$user->email){
            Session::flash('error','Your account has no email on file, so a reset code cannot be sent.');
            return redirect()->back();
        }

        $sendKey = 'password-reset-send:'.$user->id;
        if(\RateLimiter::tooManyAttempts($sendKey,1)){
            $seconds = \RateLimiter::availableIn($sendKey);
            Session::flash('error',"Please wait {$seconds} seconds before requesting another code.");
            return redirect()->back();
        }

        $code = $user->issueVerifyCode();

        if(!$this->emailPasswordResetCode($user,$code)){
            Session::flash('error','We could not send the code right now. Please try again in a moment.');
            return redirect()->back();
        }

        \RateLimiter::hit($sendKey,45);
        \RateLimiter::clear('password-reset-verify:'.$user->id);

        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        // Re-seed the session with exactly what AuthController::forgotPasswordStore() would have set, so
        // the shared /reset-password page (and everything after it) just works, unchanged.
        Session::put('password_reset_user_id',$user->id);
        Session::put('password_reset_resend_at',now()->addSeconds(45)->timestamp);

        return redirect()->route('resetPassword');
    }

    private function emailPasswordResetCode(\App\Models\User $user, string $code): bool{
        return sendMail(
            $user->email,
            $user->name,
            'Your password reset code for '.general()->title,
            ['user'=>$user,'verifycode'=>$code],
            'mails.VerifyCodeMail'
        );
    }
}
