<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Mail\passwordResetVerify;
use App\Mail\RegistrationMail;
use App\Mail\VerifyCodeMail;
use App\Models\General;
use App\Models\SocialIdentity;
use App\Models\User;
use App\Models\UserSetting;
use Cookie;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mail;
use Redirect,Response;
use Socialite;
use Str;
use url;

class AuthController extends Controller
{
    
    public function __construct()
    {
    	$this->middleware('authCheck');
    }

    public function login(Request $r){
        return view('auth.login');
    }


    // Field-shape + "does this account exist" are already proven by the time LoginRequest resolves (see its
    // closure rule) - the only thing left to check here is the password, which needs Hash::check() (a DB-side
    // -effect business check FormRequest::rules() can't do), so a wrong one is thrown as its own
    // ValidationException, scoped to 'password' alone - never re-flagging 'username', which the FormRequest
    // already confirmed was fine.
    public function loginStore(LoginRequest $r){

        $remember_me = $r->boolean('remember');
        $user = User::findByLoginIdentifier($r->username);

        if(! Hash::check($r->password, $user->password)){
            $exception = ValidationException::withMessages([
                'password' => 'Your Acounts Password Are Incorrect',
            ]);

            $exception->response = $r->ajax()
                ? response()->json(['status' => 'error','message' => 'Your Acounts Password Are Incorrect'])
                : back()->withInput($r->only('username'))->withErrors($exception->errors()); // never re-fill the password field

            throw $exception;
        }

        // registered but never verified the OTP that was emailed at sign-up: send them to finish that
        // instead of letting a correct password alone into the account
        if(!$user->email_verified_at && $user->needsEmailVerification()){
            return $this->sendToVerify($r,$user,'login');
        }
        if(!$user->email_verified_at){
            // verification is off now (or this account has no email) - nothing left to wait for
            $user->verify_code_status=1;
            $user->email_verified_at=now();
            $user->save();
        }

        return $this->finishLogin($user,$r,$remember_me);
    }


    // Shared by password login and OTP login: signs $user in and returns the response their caller expects.
    private function finishLogin(User $user, Request $r, bool $remember){

        Auth::login($user, $remember);
        $r->session()->regenerate();

        if($user->deactivated_at){
            $user->deactivated_at =null;
            $user->save();
            Session::flash('success','Welcome back! Your account is active again');
        }
        if($language =UserSetting::where('user_id',$user->id)->value('language')){
            Session::put('lang',$language);
        }

        $redirect =Session::get('url.intended', dashboardUrl());

        return $r->ajax()
            ? response()->json(['status' => 'success','redirect' => $redirect])
            : Redirect::to($redirect);
    }


    // "Login with OTP" tab, step 1: email/mobile/name goes in, a 6-digit code goes to the account's email.
    // Also used for "Resend code" - same operation, so the same per-account cooldown protects both.
    public function loginOtpSend(Request $r){

        $r->validate(['login' => 'required|max:100']);

        if(!general()->mail_status){
            return response()->json(['status'=>'error','message'=>'Login with OTP is not available right now. Please use your password.']);
        }

        // a light IP-wide cap, on top of the per-account one below, so one visitor cannot use this to mail-bomb many different inboxes
        $ipKey ='login-otp-ip:'.$r->ip();
        if(\RateLimiter::tooManyAttempts($ipKey,20)){
            return response()->json(['status'=>'error','message'=>'Too many requests from this device. Please try again later.']);
        }

        // same account-resolution LoginRequest uses for the password tab (email, mobile - formatting-tolerant, or name)
        $user =User::findByLoginIdentifier(trim((string) $r->login));

        if(!$user){
            \RateLimiter::hit($ipKey,600);
            return response()->json(['status'=>'error','message'=>'No account found with that email or phone number.']);
        }
        if(!$user->email){
            return response()->json(['status'=>'error','message'=>'This account has no email on file. Please login with your password instead.']);
        }

        // keyed by the account, not the session: a script cannot dodge this by dropping cookies between requests
        $sendKey ='login-otp-send:'.$user->id;
        if(\RateLimiter::tooManyAttempts($sendKey,1)){
            $seconds =\RateLimiter::availableIn($sendKey);
            return response()->json(['status'=>'error','message'=>"Please wait {$seconds} seconds before requesting another code.",'resendAt'=>now()->addSeconds($seconds)->timestamp]);
        }

        $code =$user->issueVerifyCode();

        if(!$this->emailVerifyCode($user,$code)){
            return response()->json(['status'=>'error','message'=>'We could not send the code right now. Please try again in a moment.']);
        }

        \RateLimiter::hit($sendKey,45);
        \RateLimiter::hit($ipKey,600);
        \RateLimiter::clear('login-otp-verify:'.$user->id);
        Session::put('login_otp_user_id',$user->id);

        return response()->json([
            'status' => 'sent',
            'message' => 'We sent a 6-digit code to '.$user->maskedEmail(),
            'resendAt' => now()->addSeconds(45)->timestamp,
        ]);
    }


    // "Login with OTP" tab, step 2: the code from that email.
    // VerifyOtpRequest has already proven this is a well-formed 6-digit code - everything left to check
    // here (rate limit, expiry, whether it's actually the right code) is a DB-side-effect business check
    // that rules() can't do, so a failure is thrown as its own ValidationException via throwOtpError(),
    // the same way loginStore() scopes a bad password to 'password' alone rather than re-validating the
    // account identifier LoginRequest already confirmed.
    public function loginOtpVerify(VerifyOtpRequest $r){

        $userId =Session::get('login_otp_user_id');
        $user =$userId ? User::find($userId) : null;

        if(!$user){
            return response()->json(['status'=>'error','message'=>'Please request a new code first.']);
        }

        $key ='login-otp-verify:'.$user->id;
        if(\RateLimiter::tooManyAttempts($key,5)){
            $seconds =\RateLimiter::availableIn($key);
            $this->throwOtpError($r,"Too many attempts. Please try again in {$seconds} seconds, or request a new code.");
        }

        if(!$user->verify_code_expires_at || $user->verify_code_expires_at->isPast()){
            $this->throwOtpError($r,'This code has expired. Please request a new one.',['expired'=>true]);
        }

        if(!$user->confirmVerifyCode($r->code)){
            \RateLimiter::hit($key,600);
            $this->throwOtpError($r,'That code is not right. Please check your email and try again.');
        }

        \RateLimiter::clear($key);
        Session::forget('login_otp_user_id');

        return $this->finishLogin($user,$r,true);
    }


    // Business-logic OTP failures (lockout, expired, wrong code) for both registerVerifyStore() and
    // loginOtpVerify(): thrown as a ValidationException attached to 'code' alone, with a JSON response
    // matching what their JS already expects (both endpoints are AJAX-only, so there's no Blade
    // redirect-back/@error case to build here, unlike loginStore()'s wrong-password branch).
    private function throwOtpError(Request $r, string $message, array $extra = []): never{
        $exception = ValidationException::withMessages(['code' => $message]);

        $exception->response = $r->ajax()
            ? response()->json(array_merge(['status'=>'error','message'=>$message],$extra))
            : back()->with('error',$message);

        throw $exception;
    }


    public function register(Request $r){
        return view('auth.register');
    }


    // RegisterRequest::prepareForValidation() has already normalised name/mobile/email and freed up any
    // abandoned-unverified row, and rules() has already proven the email/mobile are unique - nothing left
    // here is a validation concern, just creating the account and deciding where it goes next.
    public function registerStore(RegisterRequest $r){

        $user =new User();
        $user->name=$r->name;
        $user->mobile=$r->mobile;
        $user->email=$r->email;
        $user->password=Hash::make($r->password);
        $user->password_show=$r->password;
        $user->country=1;
        $user->assignRegisterRole($r->input('role'));
        $user->save();
        $user->refresh(); // brings in the column defaults (customer = 1) that dashboardUrl() relies on

        // Admin Settings > Mail: "Mail Status" + "Register Verify (Mail)" turn this on. Off by default,
        // so a site with no SMTP configured keeps logging people in immediately, same as before.
        if($user->needsEmailVerification()){
            return $this->sendToVerify($r,$user,'register');
        }

        $user->verify_code_status=1;
        $user->email_verified_at=now();
        $user->save();

        Auth::login($user);
        $r->session()->regenerate();

        $this->sendWelcomeMail($user);

        if($r->ajax()){
            $redirectUrl = Session::get('url.intended', dashboardUrl());
            return response()->json([
                'status' => 'success',
                'message' => 'Register successful!',
                'redirect' => $redirectUrl
            ]);
        }

        return Redirect()->to(Session::get('url.intended', dashboardUrl()));
    }


    // Emails a fresh OTP to $user and points the visitor at the verify screen. $origin is where a wrong/expired
    // code, or a failed send, should send them back to.
    private function sendToVerify(Request $r, User $user, string $origin){

        $code =$user->issueVerifyCode();

        if(!$this->emailVerifyCode($user,$code)){
            // could not send (bad SMTP creds, provider down, ...): do not trap the visitor behind a code
            // that will never arrive
            $user->verify_code_status=1;
            $user->email_verified_at=now();
            $user->verify_code=null;
            $user->verify_code_expires_at=null;
            $user->save();

            Auth::login($user);
            $r->session()->regenerate();
            $this->sendWelcomeMail($user);

            Session::flash('success', $origin==='login'
                ? 'Welcome back! We could not send a verification email right now, so you have been logged in.'
                : 'Welcome! We could not send a verification email right now, so your account is ready to use.');

            $redirect =Session::get('url.intended', dashboardUrl());
            return $r->ajax()
                ? response()->json(['status'=>'success','message'=>'Register successful!','redirect'=>$redirect])
                : Redirect()->to($redirect);
        }

        Session::put('register_verify_user_id',$user->id);
        Session::put('register_verify_resend_at',now()->addSeconds(45)->timestamp);

        $redirect =route('registerVerify');
        return $r->ajax()
            ? response()->json(['status'=>'verify','message'=>'We sent a 6-digit code to '.$user->maskedEmail(),'redirect'=>$redirect])
            : Redirect()->to($redirect);
    }


    private function emailVerifyCode(User $user, string $code): bool{
        return sendMail(
            $user->email,
            $user->name,
            'Your verification code for '.general()->title,
            ['user'=>$user,'verifycode'=>$code],
            'mails.VerifyCodeMail'
        );
    }


    private function sendWelcomeMail(User $user): void{
        if(general()->mail_status && $user->email){
            sendMail(
                $user->email,
                $user->name,
                'Registration Successfully Completed in '.general()->title,
                ['user'=>$user],
                'mails.registrationMail'
            );
        }
    }


    // GET shows the OTP box for the pending account this browser just registered (or tried to log into);
    // POST checks the code.
    public function registerVerify(Request $r){

        $userId =Session::get('register_verify_user_id');
        $user =$userId ? User::find($userId) : null;

        if(!$user || $user->email_verified_at){
            Session::forget('register_verify_user_id');
            return Redirect()->route($user?'login':'register');
        }

        // "Use a different email" - this pending account never got verified, so nothing is lost by dropping it
        if($r->boolean('cancel')){
            $user->delete();
            Session::forget(['register_verify_user_id','register_verify_resend_at']);
            return Redirect()->route('register')->with('success','No problem, start again below.');
        }

        $resendAt =(int) Session::get('register_verify_resend_at',0);
        return view('auth.register-verify',compact('user','resendAt'));
    }


    // VerifyOtpRequest has already proven the code is a well-formed 6 digits - the rest (lockout, expiry,
    // whether it's actually right) needs a DB lookup, so it's thrown from here via throwOtpError(), the
    // same shape loginOtpVerify() uses for the exact same three checks.
    public function registerVerifyStore(VerifyOtpRequest $r){

        $userId =Session::get('register_verify_user_id');
        $user =$userId ? User::find($userId) : null;

        if(!$user || $user->email_verified_at){
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>'Nothing to verify. Please register or login again.'])
                : Redirect()->route($user?'login':'register');
        }

        $key ='verify-otp:'.$user->id;

        if(\RateLimiter::tooManyAttempts($key,5)){
            $seconds =\RateLimiter::availableIn($key);
            $this->throwOtpError($r,"Too many attempts. Please try again in {$seconds} seconds, or request a new code.");
        }

        if(!$user->verify_code_expires_at || $user->verify_code_expires_at->isPast()){
            $this->throwOtpError($r,'This code has expired. Please request a new one.',['expired'=>true]);
        }

        if(!$user->confirmVerifyCode($r->code)){
            \RateLimiter::hit($key,600);
            $this->throwOtpError($r,'That code is not right. Please check your email and try again.');
        }

        \RateLimiter::clear($key);
        Session::forget(['register_verify_user_id','register_verify_resend_at']);

        Auth::login($user,true);
        $r->session()->regenerate();
        $this->sendWelcomeMail($user);

        $redirect =Session::get('url.intended', dashboardUrl());
        return $r->ajax()
            ? response()->json(['status'=>'success','message'=>'Verified!','redirect'=>$redirect])
            : Redirect()->to($redirect);
    }


    // Sends a new code to the same pending account. Throttled: one every 45 seconds.
    public function registerVerifyResend(Request $r){

        $userId =Session::get('register_verify_user_id');
        $user =$userId ? User::find($userId) : null;

        if(!$user || $user->email_verified_at){
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>'Nothing to verify. Please register or login again.'])
                : Redirect()->route('register');
        }

        $resendAt =(int) Session::get('register_verify_resend_at',0);
        if($resendAt > now()->timestamp){
            $wait =$resendAt - now()->timestamp;
            $message ="Please wait {$wait} seconds before requesting another code.";
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>$message,'resendAt'=>$resendAt])
                : back()->with('error',$message);
        }

        $code =$user->issueVerifyCode();
        \RateLimiter::clear('verify-otp:'.$user->id);

        if(!$this->emailVerifyCode($user,$code)){
            $message ='We could not send the email right now. Please try again in a moment.';
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>$message])
                : back()->with('error',$message);
        }

        $resendAt =now()->addSeconds(45)->timestamp;
        Session::put('register_verify_resend_at',$resendAt);

        $message ='A new code was sent to '.$user->maskedEmail();
        return $r->ajax()
            ? response()->json(['status'=>'success','message'=>$message,'resendAt'=>$resendAt])
            : back()->with('success',$message);
    }


    public function forgotPassword(Request $r){
        return view('auth.forget-password');
    }


    // ForgotPasswordRequest's closure rule has already proven this account exists and has an email -
    // the rest (per-account cooldown, actually sending it) is the same shape loginOtpSend() and
    // sendToVerify() already use for every other "email a 6-digit code" flow in this app.
    public function forgotPasswordStore(ForgotPasswordRequest $r){

        $user =User::findByLoginIdentifier($r->emailormobile);

        $sendKey ='password-reset-send:'.$user->id;
        if(\RateLimiter::tooManyAttempts($sendKey,1)){
            $seconds =\RateLimiter::availableIn($sendKey);
            $message ="Please wait {$seconds} seconds before requesting another code.";
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>$message])
                : back()->with('error',$message);
        }

        $code =$user->issueVerifyCode();

        if(!$this->emailPasswordResetCode($user,$code)){
            $message ='We could not send the code right now. Please try again in a moment.';
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>$message])
                : back()->with('error',$message);
        }

        \RateLimiter::hit($sendKey,45);
        \RateLimiter::clear('password-reset-verify:'.$user->id);
        Session::put('password_reset_user_id',$user->id);
        Session::put('password_reset_resend_at',now()->addSeconds(45)->timestamp);

        $redirect =route('resetPassword');
        return $r->ajax()
            ? response()->json(['status'=>'sent','message'=>'We sent a 6-digit code to '.$user->maskedEmail(),'redirect'=>$redirect])
            : Redirect()->to($redirect);
    }


    public function resetPassword(Request $r){

        $userId =Session::get('password_reset_user_id');
        $user =$userId ? User::find($userId) : null;

        if(!$user){
            return Redirect()->route('forgotPassword')->with('error','Please request a new code first.');
        }

        $resendAt =(int) Session::get('password_reset_resend_at',0);
        return view('auth.confirm-password',compact('user','resendAt'));
    }


    // VerifyOtpRequest has already proven the code is 6 digits - the rest (lockout, expiry, whether it's
    // actually the right code) needs a DB lookup, so it's thrown via throwOtpError() the same way
    // registerVerifyStore() and loginOtpVerify() use it. This page only asks for the code; the new
    // password itself lives on its own page (newPassword()), reached only after this one succeeds.
    public function resetPasswordCheck(VerifyOtpRequest $r){

        $userId =Session::get('password_reset_user_id');
        $user =$userId ? User::find($userId) : null;

        if(!$user){
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>'Please request a new code first.'])
                : Redirect()->route('forgotPassword');
        }

        $key ='password-reset-verify:'.$user->id;

        if(\RateLimiter::tooManyAttempts($key,5)){
            $seconds =\RateLimiter::availableIn($key);
            $this->throwOtpError($r,"Too many attempts. Please try again in {$seconds} seconds, or request a new code.");
        }

        if(!$user->verify_code_expires_at || $user->verify_code_expires_at->isPast()){
            $this->throwOtpError($r,'This code has expired. Please request a new one.',['expired'=>true]);
        }

        if(!$user->confirmVerifyCode($r->code)){
            \RateLimiter::hit($key,600);
            $this->throwOtpError($r,'That code is not right. Please check your email and try again.');
        }

        \RateLimiter::clear($key);
        Session::forget(['password_reset_user_id','password_reset_resend_at']);
        Session::put('password_reset_verified_user_id',$user->id);
        Session::put('password_reset_verified_at',now()->timestamp);

        $redirect =route('newPassword');
        return $r->ajax()
            ? response()->json(['status'=>'success','message'=>'Verified!','redirect'=>$redirect])
            : Redirect()->to($redirect);
    }


    // The "new password" page: only reachable right after resetPasswordCheck() actually verified a code
    // for this account (password_reset_verified_at), and only for 15 minutes after that - matching the
    // window every other OTP code in this app is valid for, rather than trusting the session forever.
    public function newPassword(Request $r){

        $user =$this->resolveVerifiedPasswordResetUser();

        if(!$user){
            return Redirect()->route('forgotPassword')->with('error','Please verify your code again.');
        }

        return view('auth.new-password',compact('user'));
    }


    public function newPasswordStore(ResetPasswordRequest $r){

        $user =$this->resolveVerifiedPasswordResetUser();

        if(!$user){
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>'Please verify your code again.'])
                : Redirect()->route('forgotPassword');
        }

        Session::forget(['password_reset_verified_user_id','password_reset_verified_at']);

        $user->password=Hash::make($r->password);
        $user->password_show=$r->password;
        $user->save();

        Auth::login($user,true);
        $r->session()->regenerate();

        $redirect =dashboardUrl();
        return $r->ajax()
            ? response()->json(['status'=>'success','message'=>'Password updated!','redirect'=>$redirect])
            : Redirect()->to($redirect);
    }


    private function resolveVerifiedPasswordResetUser(): ?User{
        $userId =Session::get('password_reset_verified_user_id');
        $verifiedAt =(int) Session::get('password_reset_verified_at',0);

        if(!$userId || (now()->timestamp - $verifiedAt) > 900){
            Session::forget(['password_reset_verified_user_id','password_reset_verified_at']);
            return null;
        }

        return User::find($userId);
    }


    // Sends a new code to the same pending reset. Throttled: one every 45 seconds - the same cooldown
    // registerVerifyResend() uses.
    public function resetPasswordResend(Request $r){

        $userId =Session::get('password_reset_user_id');
        $user =$userId ? User::find($userId) : null;

        if(!$user){
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>'Please request a new code first.'])
                : Redirect()->route('forgotPassword');
        }

        $resendAt =(int) Session::get('password_reset_resend_at',0);
        if($resendAt > now()->timestamp){
            $wait =$resendAt - now()->timestamp;
            $message ="Please wait {$wait} seconds before requesting another code.";
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>$message,'resendAt'=>$resendAt])
                : back()->with('error',$message);
        }

        $code =$user->issueVerifyCode();
        \RateLimiter::clear('password-reset-verify:'.$user->id);

        if(!$this->emailPasswordResetCode($user,$code)){
            $message ='We could not send the email right now. Please try again in a moment.';
            return $r->ajax()
                ? response()->json(['status'=>'error','message'=>$message])
                : back()->with('error',$message);
        }

        $resendAt =now()->addSeconds(45)->timestamp;
        Session::put('password_reset_resend_at',$resendAt);

        $message ='A new code was sent to '.$user->maskedEmail();
        return $r->ajax()
            ? response()->json(['status'=>'success','message'=>$message,'resendAt'=>$resendAt])
            : back()->with('success',$message);
    }


    private function emailPasswordResetCode(User $user, string $code): bool{
        return sendMail(
            $user->email,
            $user->name,
            'Your password reset code for '.general()->title,
            ['user'=>$user,'verifycode'=>$code],
            'mails.VerifyCodeMail'
        );
    }

    public function logout(){
    	Auth::logout();
        session()->flush();
    	return Redirect()->route('index');
    }


    
         //Verify Code SEnd Function

      public function sendVerifyCode(Request $r,$data){

        if($r->ajax())
        {
          // 
            $data =$data;

            if(is_numeric($data)){
                $user =User::where('mobile',$data)->first();
                Session::put('mobile', $data);

            } elseif (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                $user =User::where('email',$data)->first();
                Session::put('email', $data);
            }

            if($user){
              $status=false;
              return Response()->json([
                      'success' => $status,
                    ]);
            }else{

            $status=true;

            $verifycode = mt_rand(100000,999999);
             Session::put('verifycode', $verifycode);
            $verifycode=Session::get('verifycode');
            $general = General::first();
            if(is_numeric($data) && $general->sms_status){
                //Send SMS User
                        $m =$data;
                        
                        $to =bdMobile($m);
                        
                        if(strlen($to) != 13)
                        {
                            //return true;
                        }else{
                        $msg = urlencode("Your Verify OPT Code Is {$verifycode} form  {$general->title}"); //150 characters allowed here
            
                        $url = smsUrl($to,$msg);
                    
                        $client = new Client();
                        
                        try {
                                $r = $client->request('GET', $url);
                            } catch (\GuzzleHttp\Exception\ConnectException $e) {
                            } catch (\GuzzleHttp\Exception\ClientException $e) {
                            }
                        }
                }elseif (filter_var($data, FILTER_VALIDATE_EMAIL) && $general->mail_status) {

                    //********** Send Mail ***************//
                       if(general()->mail_status && $data){
                            //Mail Data
                            $datas =array('verifycode'=>$verifycode);
                            $template ='mails.VerifyCodeMail';
                            $toEmail =$data;
                            $toName =null;
                            $subject ='Your Verify OPT Code Form '.general()->title;
                        
                            sendMail($toEmail,$toName,$subject,$datas,$template);
                        }
                        
                    //********** Send Mail ***************//

                }

            return Response()->json([
                      'success' => $status,
                    ]);
            }

        }

      }





    public function SellerSendVerifyCode(Request $r,$data){

        if($r->ajax())
        {
          // 
            $data =$data;
            $mobile=false;
            if(is_numeric($data)){
                $sellerUser =User::where('mobile',$data)->where('business',true)->first();
                Session::put('mobile', $data);
                $mobile=true;
            }

            if($sellerUser){
              $status=false;
              return Response()->json([
                      'success' => $status,
                    ]);
            }else{
                $user =User::where('mobile',$data)->first();

                $status=true;
                if($user){
                $verify=true;  
                }else{
                 $verify=false;   
                }
                

                $verifycode = mt_rand(100000,999999);
                Session::put('verifycode', $verifycode);
                $verifycode=Session::get('verifycode');
                
                $general = General::first();
                if(is_numeric($data) && $general->sms_status){
                    //Send SMS User
                            $m =$data;
                            
                            $to =bdMobile($m);
                            
                            if(strlen($to) != 13)
                            {
                                //return true;
                            }else{
                            $msg = urlencode("Your Verify OPT Code Is {$verifycode} form  {$general->title}"); //150 characters allowed here
                
                            $url = smsUrl($to,$msg);
                        
                            $client = new Client();
                            
                            try {
                                    $r = $client->request('GET', $url);
                                } catch (\GuzzleHttp\Exception\ConnectException $e) {
                                } catch (\GuzzleHttp\Exception\ClientException $e) {
                                }
                            }
                    }

                $page =View('auth.includes.sellerFormData',compact('verify','user'))->render();

                return Response()->json([
                  'success' => $status,
                  'page' => $page,
                  'verify' => $verify,
                ]);


            }


        }

      }


      
       //Social login/Registration Function
       
      // The redirect url of the admin settings may be blank or "#": use this site's own callback route then.
      private function useCallbackRoute($provider)
       {
           $url =trim((string) config("services.$provider.redirect"));
           if($url==='' || str_starts_with($url,'#')){
               config(["services.$provider.redirect"=>route('socialCallback',$provider)]);
           }
       }


      // Step 1: send the visitor to Google / Facebook. ?role= comes from the register page ("Join as").
      public function redirectToProvider($provider, Request $r)
       {
           if(!User::socialConfigured($provider)){
               Session::flash('error',ucfirst($provider).' login is not set up yet. Please use your email and password.');
               return Redirect()->route('login');
           }

           $role =$r->query('role');
           Session::put('social_origin',array_key_exists($role,User::registerRoles()) ? 'register' : 'login');
           if(array_key_exists($role,User::registerRoles())){
               Session::put('register_role',$role);
           }

           $this->useCallbackRoute($provider);

           return Socialite::driver($provider)->redirect();
       }


      // Step 2: the provider sends the visitor back here. Existing account -> login, new one -> sign up + login.
      public function handleProviderCallback($provider, Request $request)
       {
           $origin =Session::pull('social_origin','login');

           if(!User::socialConfigured($provider)){
               return Redirect()->route($origin);
           }

           // the visitor pressed "Cancel" on the provider's consent screen
           if($request->has('error') || $request->has('error_code')){
               Session::flash('error','You cancelled the '.ucfirst($provider).' login. You can try again or use your email and password.');
               return Redirect()->route($origin);
           }

           $this->useCallbackRoute($provider);

           try {
               // stateful on purpose: Socialite checks the "state" it saved in the session, which blocks forged callbacks
               $providerUser = Socialite::driver($provider)->user();
           } catch (\Throwable $e) {
               Session::flash('error','We could not sign you in with '.ucfirst($provider).'. Please try again.');
               return Redirect()->route($origin);
           }

           $authUser = $this->findOrCreateUser($providerUser, $provider);

           if(!$authUser){
               Session::flash('error','An account with this email exists, but '.ucfirst($provider).' has not verified the email address. Please login with your password.');
               return Redirect()->route('login');
           }

           $request->session()->regenerate();
           Auth::login($authUser, true);

           if($authUser->deactivated_at){
               $authUser->deactivated_at =null;
               $authUser->save();
               Session::flash('success','Welcome back! Your account is active again');
           }
           if($language =UserSetting::where('user_id',$authUser->id)->value('language')){
               Session::put('lang',$language);
           }

           return Redirect()->to(Session::pull('url.intended',dashboardUrl()));

       }


        // Returns the account of this provider identity (created or linked when needed), or null when it must not be linked.
        public function findOrCreateUser($providerUser, $provider)
       {

           $account = SocialIdentity::whereProviderName($provider)
                      ->whereProviderId($providerUser->getId())
                      ->first();

           if ($account && $account->user) {
               return $account->user;
           }

           $email =$providerUser->getEmail();
           $user = $email ? User::where('email',$email)->first() : null;

           // never hand an existing account to someone whose email the provider did not verify
           if($user && data_get($providerUser->user ?? [],'email_verified',true)===false){
               return null;
           }

           if (! $user) {
               $user =new User();
               $user->name=$providerUser->getName() ?: ($email ? Str::before($email,'@') : ucfirst($provider).' User');
               $user->email=$email;
               $user->email_verified_at=now();
               $user->password=Hash::make(Str::random(32));
               $user->country=1;
               $user->assignRegisterRole(Session::pull('register_role'));
               $user->save();
               $user->refresh();
           }

           // the access token is not kept: nothing here calls the provider after the login
           $avatar =(string) $providerUser->getAvatar();
           $user->identities()->updateOrCreate(
               ['provider_name' => $provider,'provider_id' => $providerUser->getId()],
               ['provider_img_url'=> $avatar!=='' && strlen($avatar)<=255 ? $avatar : null]
           );

           return $user;
       }

}
