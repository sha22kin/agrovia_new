@extends(welcomeTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle('Login')}}</title>
@endsection 
@section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{websiteTitle('Login')}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('login')}}" />
<link rel="canonical" href="{{route('login')}}">
@endsection 
@push('css')










<style>
  :root{
    --green-900:#1c4a24;
    --green-700:#276e33;
    --green-600:#2f8a3d;
    --green-500:#3fae4d;
    --green-100:#eef7ee;
    --green-50:#f6faf5;
    --ink:#1f2a20;
    --muted:#6b786c;
    --white:#ffffff;
    --border:#e2ebe1;
    --shadow:0 10px 30px rgba(28,74,36,0.12);
    --radius-lg:20px;
    --radius-md:14px;
    --radius-sm:10px;
    --green-400:#5cc768;
    --danger:#e0563f;
    --danger-bg:#fdeceb;
  }

  *{box-sizing:border-box;}


  /* ===== Layout container ===== */
  .page-container{
    width:100%;
    max-width:1100px;
    display:flex;
    flex-direction:column;
    gap:28px;
    animation:fadeInUp .6s ease both;
    margin: 30px auto;
  }

  .auth-card{
    display:flex;
    flex-wrap:wrap;
    background:var(--white);
    border-radius:var(--radius-lg);
    box-shadow:var(--shadow);
    overflow:hidden;
    min-height:600px;
  }

  /* ===== Columns ===== */
  .col{
    flex:1 1 0;
    min-width:320px;
  }

  .col-image{
    position:relative;
    background:
      linear-gradient(180deg, rgba(20,50,20,0.05) 0%, rgba(10,30,10,0.55) 100%),
      url('{{asset('public/welcome/images/auth-login.jpg')}}') 42% center/cover no-repeat;
    min-height:340px;
  }

  .col-image::after{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(180deg, transparent 40%, rgba(10,30,10,0.65) 100%);
  }

  .col-form{
    padding:44px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    gap:22px;
  }

  /* ===== Brand ===== */
  .brand{
    display:flex;
    align-items:center;
    gap:10px;
  }
  .brand .leaf{
    width:34px;height:34px;
    border-radius:50%;
    background:var(--green-100);
    display:flex;align-items:center;justify-content:center;
    color:var(--green-600);
    font-size:18px;
    transition:transform .3s ease;
  }
  .brand:hover .leaf{transform:rotate(-18deg) scale(1.08);}
  .brand h1{
    font-size:22px;
    margin:0;
    color:var(--green-700);
    letter-spacing:.2px;
  }
  .brand span{
    display:block;
    font-size:12px;
    color:var(--muted);
    font-weight:400;
  }

  .headline h2{
    margin:0 0 4px;
    font-size:24px;
  }
  .headline p{
    margin:0;
    color:var(--muted);
    font-size:14px;
  }

  /* ===== Tabs ===== */
  .tabs{
    display:flex;
    border-bottom:1px solid var(--border);
  }
  .tab{
    flex:1;
    text-align:center;
    padding:10px 0 12px;
    font-size:14px;
    font-family:inherit;
    color:var(--muted);
    cursor:pointer;
    position:relative;
    border:none;
    background:none;
    outline:none;
    transition:color .25s ease;
  }
  .tab.active{
    color:var(--green-700);
    font-weight:600;
  }
  .tab.active::after{
    content:"";
    position:absolute;
    left:0;right:0;bottom:-1px;
    height:2px;
    background:var(--green-600);
    animation:growLine .3s ease both;
  }
  .tab:not(.active):hover{color:var(--green-600);}

  @keyframes growLine{
    from{transform:scaleX(0);}
    to{transform:scaleX(1);}
  }

  /* ===== Login with OTP panel ===== */
  .panel{ display:none; flex-direction:column; gap:18px; }
  .panel.active{ display:flex; }

  .otp-alert{
    display:flex; align-items:center; gap:8px;
    padding:11px 14px;
    border-radius:var(--radius-sm);
    font-size:13px;
    background:var(--danger-bg);
    color:#a3372a;
    border:1px solid #f5c6c1;
  }
  .otp-alert.success{ background:var(--green-100); color:var(--green-700); border-color:#cfe8cf; }
  .otp-alert:empty{ display:none; }

  .otp-sent-to{ font-size:13.5px; color:var(--muted); }
  .otp-sent-to strong{ color:var(--ink); }

  .otp-group{ display:flex; justify-content:center; gap:clamp(6px,2.4vw,12px); }
  .otp-box{
    width:clamp(40px,13vw,52px);
    height:clamp(48px,15vw,58px);
    font-size:clamp(20px,5.6vw,24px);
    text-align:center;
    font-weight:700;
    color:var(--green-900);
    border:1.5px solid var(--border);
    border-radius:var(--radius-sm);
    background:var(--green-50);
    outline:none;
    caret-color:var(--green-600);
    transition:border-color .2s ease, box-shadow .2s ease, background .2s ease, transform .15s ease;
  }
  .otp-box:focus{
    border-color:var(--green-500);
    background:var(--white);
    box-shadow:0 0 0 4px rgba(63,174,77,0.16);
    transform:translateY(-1px);
  }
  .otp-box.filled{ border-color:var(--green-400); background:var(--white); }
  .otp-group.invalid .otp-box{ border-color:var(--danger); background:#fff8f7; }
  .otp-group.shake .otp-box{ animation:otpShake .38s ease; }
  .otp-group.success .otp-box{ border-color:var(--green-500); background:var(--green-100); color:var(--green-700); }
  @keyframes otpShake{
    10%,90%{transform:translateX(-1px);} 20%,80%{transform:translateX(2px);}
    30%,50%,70%{transform:translateX(-4px);} 40%,60%{transform:translateX(4px);}
  }
  @keyframes spin{ to{ transform:rotate(360deg); } }

  .otp-resend-row{ display:flex; align-items:center; justify-content:space-between; font-size:13.5px; }
  .otp-resend-row button{
    border:none; background:none; padding:0; color:var(--green-600); font-weight:700; cursor:pointer; font-size:13.5px;
  }
  .otp-resend-row button:disabled{ color:var(--muted); cursor:not-allowed; font-weight:600; }
  .otp-back{ font-size:13px; color:var(--muted); background:none; border:none; padding:0; cursor:pointer; text-decoration:underline; }

  /* ===== Form fields ===== */
  .field{
    display:flex;
    flex-direction:column;
    gap:6px;
  }
  .form-alert{
    display:flex; align-items:center; gap:8px;
    padding:11px 14px;
    border-radius:var(--radius-sm);
    font-size:13px;
    background:var(--danger-bg);
    color:#a3372a;
    border:1px solid #f5c6c1;
  }
  .form-alert:empty{ display:none; }
  .field-error{
    font-size:12px;
    color:#c0392b;
    margin-top:2px;
  }
  .field-error:empty{ display:none; }
  .field.invalid .input-wrap input{
    border-color:var(--danger);
    background:#fff8f7;
  }
  .btn-primary[disabled]{ opacity:.7; cursor:not-allowed; transform:none; }
  .btn-primary .spinner{
    width:15px;height:15px;border-radius:50%;
    border:2px solid rgba(255,255,255,.45);
    border-top-color:#fff;
    animation:spin .7s linear infinite;
    display:none;
    margin-right:8px;
    vertical-align:-2px;
  }
  .btn-primary.is-busy .spinner{ display:inline-block; }
  .field label{
    font-size:13px;
    font-weight:600;
    color:var(--ink);
  }
  .input-wrap{
    position:relative;
    display:flex;
    align-items:center;
  }
  .input-wrap>svg{
    position:absolute;
    left:12px;
    width:16px;height:16px;
    color:var(--muted);
    pointer-events:none;
  }
  .input-wrap input{
    width:100%;
    padding:12px 14px 12px 36px;
    border:1px solid var(--border);
    border-radius:var(--radius-sm);
    font-size:14px;
    background:var(--green-50);
    outline:none;
    transition:border-color .25s ease, box-shadow .25s ease, background .25s ease;
  }
  .input-wrap input:focus{
    border-color:var(--green-500);
    background:var(--white);
    box-shadow:0 0 0 4px rgba(63,174,77,0.15);
  }
  .input-wrap.has-eye input{padding-right:44px;}
  .toggle-eye{
    position:absolute;
    right:8px;
    top:50%;
    transform:translateY(-50%);
    width:32px;height:32px;
    padding:0;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    color:var(--muted);
    background:none;
    border:none;
    border-radius:50%;
    display:flex;
    transition:color .2s ease, background .2s ease;
  }
  .toggle-eye:hover,.toggle-eye:focus-visible{color:var(--green-700);background:var(--green-100);outline:none;}
  .toggle-eye svg{width:16px;height:16px;}
  .toggle-eye .eye-off{display:none;}
  .toggle-eye.is-visible .eye-on{display:none;}
  .toggle-eye.is-visible .eye-off{display:block;}

  .row-between{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    font-size:13px;
  }
  .row-between .rememberMe{
    display:flex;
    align-items:center;
    gap:6px;
    margin:0;
    cursor:pointer;
  }
  .link{
    color:var(--green-600);
    text-decoration:none;
    font-weight:600;
    transition:color .2s ease;
  }
  .link:hover{color:var(--green-800,#1c4a24);text-decoration:underline;}

  /* ===== Buttons ===== */
  .btn-primary{
    border:none;
    border-radius:999px;
    padding:14px 0;
    background:linear-gradient(135deg,var(--green-600),var(--green-500));
    color:#fff;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:transform .2s ease, box-shadow .2s ease, filter .2s ease;
    box-shadow:0 8px 18px rgba(47,138,61,0.35);
    text-align: center;
  }
  .btn-primary:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 24px rgba(47,138,61,0.45);
    filter:brightness(1.04);
  }
  .btn-primary:active{transform:translateY(0);}

  .divider{
    display:flex;
    align-items:center;
    gap:10px;
    color:var(--muted);
    font-size:12px;
  }
  .divider::before,.divider::after{
    content:"";
    flex:1;
    height:1px;
    background:var(--border);
  }

  .social-list{
    display:flex;
    flex-direction:column;
    gap:10px;
  }
  .btn-social{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    padding:11px 0;
    border:1px solid var(--border);
    border-radius:var(--radius-sm);
    background:var(--white);
    font-size:14px;
    font-weight:600;
    color:var(--ink);
    cursor:pointer;
    transition:border-color .2s ease, transform .2s ease, box-shadow .2s ease, background .2s ease;
  }
  .btn-social:hover{
    border-color:var(--green-500);
    background:var(--green-50);
    transform:translateY(-1px);
    box-shadow:0 6px 14px rgba(28,74,36,0.10);
  }
  .btn-social svg{width:18px;height:18px;}
  a.btn-social{text-decoration:none;}

  .signup-note{
    text-align:center;
    font-size:13px;
    color:var(--muted);
  }

  /* ===== Feature strip ===== */
  .features{
    display:flex;
    flex-wrap:wrap;
    background:var(--green-100);
    border-radius:var(--radius-lg);
    padding:26px 20px;
    gap:16px;
  }
  .feature-col{
    flex:1 1 200px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:10px;
    text-align:center;
    padding:10px;
    border-radius:var(--radius-md);
    transition:transform .25s ease, background .25s ease;
  }
  .feature-col:hover{
    transform:translateY(-4px);
    background:var(--white);
    box-shadow:0 10px 20px rgba(28,74,36,0.08);
  }
  .feature-icon{
    width:44px;height:44px;
    border-radius:50%;
    background:var(--white);
    display:flex;align-items:center;justify-content:center;
    color:var(--green-600);
    transition:background .25s ease, transform .25s ease;
  }
  .feature-col:hover .feature-icon{
    background:var(--green-500);
    color:var(--white);
    transform:scale(1.08) rotate(-6deg);
  }
  .feature-col p{
    margin:0;
    font-size:13px;
    color:var(--ink);
    font-weight:600;
    line-height:1.4;
  }

  @keyframes fadeInUp{
    from{opacity:0; transform:translateY(18px);}
    to{opacity:1; transform:translateY(0);}
  }

  @media (max-width:720px){
    .col-image{min-height:220px;}
    .col-form{padding:32px 24px;}
  }
</style>











@endpush 
@section('contents')

{{--<div class="pageHead">
    <div class="container">
        <h1>Login</h1>
    </div>
</div>

<div class="authSection">
	<div class="container">
	    <div class="row">
	        <div class="col-md-3"></div>
	        <div class="col-md-6">
	            <div class="login-part">
            		<h4>Hello there</h4>
            		<p>Sign In now for great values at FSBS.</p>
            		<form class="form-horizontal form-simple" action="{{route('login')}}" method="post">
                        @csrf
                        <div>
                            @if($errors->has('username'))
                                <span style="color:red;display: block;">{{ $errors->first('username') }}</span>
                            @endif
                            @if($errors->has('password'))
                                <span style="color:red;display: block;">{{ $errors->first('password') }}</span>
                            @endif
                            @if (session('error'))
                            <span style="color:red;display: block;">{{ session('error') }}</span>
                            @endif
                        </div>
                        
            			<div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                            <input type="text" value="{{old('username')}}" name="username" class="form-control control-section" placeholder="Email Address" required="">
                        </div>
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control control-section password" id="password" name="password" placeholder="Password" required="">
                        </div>
            			
            			<div class="rememberMe">
            				<input type="checkbox"/> Stay Signed In
            			</div>
            			
            			<div>
            				<button type="submit" class="btn btn-success submitbutton">LOG IN</button>
            			</div>
            		</form>
            		
                    <div class="row">
                        <div class="col-12">
                            <a class="lastPass" href="{{route('forgotPassword')}}">Lost your password?</a>
                        </div>
                        <div class="col-12">
                            <h5>Don’t have an account?</h5>
                            <a class="regiBtn" href="{{route('register')}}">Registration</a>
                        </div>
                    </div>
            		
        		</div>
	        </div>
	        <div class="col-md-3"></div>
	    </div>

	</div>
</div>
<br>--}}


<div class="page-container">

  <div class="auth-card">

    <div class="col col-image"></div>


    <div class="col col-form">
      <div class="brand">
        <span class="leaf">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C7 2 3 6 3 11c0 5 4 9 9 9s9-4 9-9c0-1-5-1-9-1s-9 0-9 1"/></svg>
        </span>
        <div>
          <h1>{{ general()->title }}</h1>
          <span>{{ general()->subtitle ?: 'Empowering Farmers' }}</span>
        </div>
      </div>

      <div class="headline">
        <h2>Welcome Back!</h2>
        <p>Login to your {{ general()->title }} account</p>
      </div>

      @php $otpLoginEnabled = (bool) general()->mail_status; @endphp
      <div class="tabs">
        <button type="button" class="tab active" data-tab="password">Login</button>
        @if($otpLoginEnabled)
        <button type="button" class="tab" data-tab="otp">Login with OTP</button>
        @endif
      </div>

     <form class="field-group panel active" id="panelPassword" data-tab-panel="password" action="{{ route('login') }}" method="POST" style="flex-direction:column;gap:18px;" novalidate>
    @csrf

    {{-- Validation Errors --}}
    <div class="form-alert" id="loginAlert" role="alert">{{ $errors->first('username') ?: session('error') ?: session('loginfail') ?: session('loginfailP') }}</div>

    {{-- Email / Phone --}}
    <div class="field {{ $errors->has('username') ? 'invalid' : '' }}">
        <label for="username">Email or Phone Number</label>

        <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16v16H4z" opacity="0"/>
                <path d="M22 6l-10 7L2 6"/>
                <path d="M2 6h20v12H2z"/>
            </svg>

            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username') }}"
                placeholder="Enter your email or phone number"
                autocomplete="username"
                required
            >
        </div>
        <span class="field-error" data-error="username">{{ $errors->first('username') }}</span>
    </div>

    {{-- Password --}}
    <div class="field {{ $errors->has('password') ? 'invalid' : '' }}">
        <label for="password">Password</label>

        <div class="input-wrap has-eye">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="10" rx="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
            >

            <button type="button" class="toggle-eye" aria-label="Show password" aria-pressed="false">
                <svg class="eye-on" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg class="eye-off" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-7-11-7a18.5 18.5 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.1 9.1 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                    <path d="M1 1l22 22"/>
                </svg>
            </button>
        </div>
        <span class="field-error" data-error="password">{{ $errors->first('password') }}</span>
    </div>

    {{-- Remember / Forgot --}}
    <div class="row-between">
        <label class="rememberMe">
            <input type="checkbox" name="remember" value="1">
            Stay Signed In
        </label>

        <a href="{{ route('forgotPassword') }}" class="link">Forgot Password?</a>
    </div>

    {{-- Login Button --}}
    <button type="submit" class="btn-primary" id="loginSubmitBtn">
        <span class="spinner"></span>
        <span class="label">Login</span>
    </button>

</form>

@if($otpLoginEnabled)
<div class="panel" id="panelOtp" data-tab-panel="otp">

    <div class="otp-alert" id="otpAlert" role="alert"></div>

    {{-- Step 1: who is logging in --}}
    <div class="field" id="otpStepIdentify">
        <label for="otpLogin">Email or Phone Number</label>
        <div class="input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16v16H4z" opacity="0"/>
                <path d="M22 6l-10 7L2 6"/>
                <path d="M2 6h20v12H2z"/>
            </svg>
            <input type="text" id="otpLogin" placeholder="Enter your email or phone number" autocomplete="username">
        </div>
        <button type="button" class="btn-primary" id="otpSendBtn" style="margin-top:18px;">Send Code</button>
    </div>

    {{-- Step 2: enter the code --}}
    <div id="otpStepVerify" style="display:none;flex-direction:column;gap:18px;">
        <p class="otp-sent-to">Code sent to <strong id="otpSentTo"></strong></p>

        <div class="otp-group" id="otpGroup">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" class="otp-box" inputmode="numeric" pattern="[0-9]*" maxlength="1" autocomplete="off">
            @endfor
        </div>

        <button type="button" class="btn-primary" id="otpVerifyBtn">
            <span class="spinner" id="otpSpinner" style="display:none;width:15px;height:15px;border-radius:50%;border:2px solid rgba(255,255,255,.45);border-top-color:#fff;animation:spin .7s linear infinite;"></span>
            <span id="otpVerifyLabel">Verify &amp; Login</span>
        </button>

        <div class="otp-resend-row">
            <button type="button" class="otp-back" id="otpBackBtn">&larr; Use a different email or phone</button>
            <button type="button" id="otpResendBtn">Resend code</button>
        </div>
    </div>

</div>
@endif

      @php
        $socialProviders = \App\Models\User::socialProviders();
        $socialIcons = [
          'google' => '<path fill="#EA4335" d="M12 10.2v3.9h5.5c-.24 1.4-1.7 4.1-5.5 4.1-3.3 0-6-2.7-6-6.1s2.7-6.1 6-6.1c1.9 0 3.2.8 3.9 1.5l2.7-2.6C16.9 3.3 14.7 2.3 12 2.3 6.9 2.3 2.7 6.5 2.7 11.6S6.9 20.9 12 20.9c6.9 0 9.6-4.8 9.6-7.3 0-.5 0-.9-.1-1.3H12z"/>',
          'facebook' => '<path fill="#1877F2" d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12"/>',
        ];
      @endphp
      @if(count($socialProviders))
      <div class="divider">or continue with</div>

      <div class="social-list">
        @foreach($socialProviders as $key => $label)
        <a class="btn-social" href="{{ route('socialRedirect', $key) }}">
          <svg viewBox="0 0 24 24">{!! $socialIcons[$key] !!}</svg>
          Continue with {{ $label }}
        </a>
        @endforeach
      </div>
      @endif

      <p class="signup-note">Don't have an account? <a href="{{route('register')}}" class="link">Sign Up</a></p>
    </div>
  </div>

  <!-- Feature strip: 4 columns -->
  <div class="features">
    <div class="feature-col">
      <span class="feature-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/></svg>
      </span>
      <p>Trusted by Thousands of Farmers</p>
    </div>
    <div class="feature-col">
      <span class="feature-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7l2-4h14l2 4"/><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 11h8"/></svg>
      </span>
      <p>Best Market Prices for Your Products</p>
    </div>
    <div class="feature-col">
      <span class="feature-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6l8-4z"/></svg>
      </span>
      <p>Secure &amp; Safe Transactions</p>
    </div>
    <div class="feature-col">
      <span class="feature-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/></svg>
      </span>
      <p>Grow Your Business Faster</p>
    </div>
  </div>

</div>







@endsection
@push('js')
@include('auth.partials.password-toggle')
<script>
(function() {
    var form = document.getElementById('panelPassword');
    var alertBox = document.getElementById('loginAlert');
    var submitBtn = document.getElementById('loginSubmitBtn');
    var usernameField = form.querySelector('#username').closest('.field');
    var passwordField = form.querySelector('#password').closest('.field');
    var usernameInput = document.getElementById('username');
    var passwordInput = document.getElementById('password');

    function setFieldError(field, input, message) {
        var box = field.querySelector('[data-error]');
        if (box) box.textContent = message || '';
        field.classList.toggle('invalid', !!message);
    }
    function clearErrors() {
        alertBox.textContent = '';
        setFieldError(usernameField, usernameInput, '');
        setFieldError(passwordField, passwordInput, '');
    }
    [usernameInput, passwordInput].forEach(function(input) {
        input.addEventListener('input', function() {
            setFieldError(input.closest('.field'), input, '');
            alertBox.textContent = '';
        });
    });

    function busy(on) {
        submitBtn.disabled = on;
        submitBtn.classList.toggle('is-busy', on);
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();

        // catches empty fields immediately, without waiting on the server
        var username = usernameInput.value.trim();
        var password = passwordInput.value;
        var hasError = false;
        if (!username) { setFieldError(usernameField, usernameInput, 'Please enter your email, phone number or name.'); hasError = true; }
        if (!password) { setFieldError(passwordField, passwordInput, 'Please enter your password.'); hasError = true; }
        if (hasError) { (username ? passwordInput : usernameInput).focus(); return; }

        busy(true);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value
            },
            body: new FormData(form),
            credentials: 'same-origin'
        }).then(function(res) { return res.json().catch(function() { return {}; }); })
          .then(function(res) {
            if (res.status === 'success' || res.status === 'verify') {
                submitBtn.querySelector('.label').textContent = 'Redirecting…';
                window.location.href = res.redirect;
                return;
            }
            if (res.status === 'errors') {
                var first = null;
                Object.keys(res.datas).forEach(function(k) {
                    var field = k === 'username' ? usernameField : (k === 'password' ? passwordField : null);
                    var input = k === 'username' ? usernameInput : (k === 'password' ? passwordInput : null);
                    if (field) setFieldError(field, input, res.datas[k][0]);
                    first = first || input;
                });
                if (first) first.focus();
            } else {
                alertBox.textContent = res.message || 'Something went wrong. Please try again.';
            }
            busy(false);
        }).catch(function() {
            alertBox.textContent = 'Could not reach the server. Please check your connection and try again.';
            busy(false);
        });
    });
})();
</script>
@if($otpLoginEnabled)
<script>
(function() {
    var tabs = document.querySelectorAll('.tab[data-tab]');
    var panels = document.querySelectorAll('[data-tab-panel]');
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            tabs.forEach(function(t) { t.classList.toggle('active', t === tab); });
            panels.forEach(function(p) { p.classList.toggle('active', p.dataset.tabPanel === tab.dataset.tab); });
        });
    });

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var alertBox = document.getElementById('otpAlert');
    var stepIdentify = document.getElementById('otpStepIdentify');
    var stepVerify = document.getElementById('otpStepVerify');
    var loginInput = document.getElementById('otpLogin');
    var sendBtn = document.getElementById('otpSendBtn');
    var sentTo = document.getElementById('otpSentTo');
    var group = document.getElementById('otpGroup');
    var boxes = Array.prototype.slice.call(group.querySelectorAll('.otp-box'));
    var verifyBtn = document.getElementById('otpVerifyBtn');
    var verifyLabel = document.getElementById('otpVerifyLabel');
    var spinner = document.getElementById('otpSpinner');
    var backBtn = document.getElementById('otpBackBtn');
    var resendBtn = document.getElementById('otpResendBtn');
    var resendAt = 0;
    var resendTimer = null;

    function setAlert(message, success) {
        alertBox.textContent = message || '';
        alertBox.classList.toggle('success', !!success);
    }

    function post(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data || {}).toString(),
            credentials: 'same-origin'
        }).then(function(res) { return res.json().catch(function() { return {}; }); });
    }

    function tickResend() {
        var left = resendAt - Math.floor(Date.now() / 1000);
        clearTimeout(resendTimer);
        if (left > 0) {
            resendBtn.disabled = true;
            resendBtn.textContent = 'Resend in ' + left + 's';
            resendTimer = setTimeout(tickResend, 1000);
        } else {
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend code';
        }
    }

    function syncHidden() {
        return boxes.map(function(b) { return b.value; }).join('');
    }
    function focusBox(i) { if (boxes[i]) { boxes[i].focus(); boxes[i].select(); } }
    function resetBoxes(focusFirst) {
        boxes.forEach(function(b) { b.value = ''; b.classList.remove('filled'); });
        group.classList.remove('invalid', 'success');
        if (focusFirst) focusBox(0);
    }
    function markInvalid() {
        group.classList.add('invalid');
        group.classList.remove('shake');
        void group.offsetWidth;
        group.classList.add('shake');
        setTimeout(function() { group.classList.remove('shake'); }, 400);
    }
    function setBusy(busy) {
        verifyBtn.disabled = busy;
        spinner.style.display = busy ? 'inline-block' : 'none';
    }

    function doVerify() {
        var code = syncHidden();
        if (code.length < 6) return;
        setAlert('');
        group.classList.remove('invalid');
        setBusy(true);

        post('{{ route('loginOtpVerify') }}', { code: code }).then(function(res) {
            if (res.status === 'success') {
                group.classList.add('success');
                verifyLabel.textContent = 'Verified! Redirecting…';
                setTimeout(function() { window.location.href = res.redirect; }, 350);
                return;
            }
            if (res.status === 'errors') {
                setAlert((res.datas.code && res.datas.code[0]) || 'Please enter the 6-digit code.');
            } else {
                setAlert(res.message || 'Something went wrong. Please try again.');
                if (res.expired) resetBoxes(true);
            }
            markInvalid();
            setBusy(false);
        }).catch(function() {
            setAlert('Could not reach the server. Please check your connection and try again.');
            setBusy(false);
        });
    }

    boxes.forEach(function(box, i) {
        box.addEventListener('focus', function() { box.select(); });
        box.addEventListener('input', function() {
            box.value = box.value.replace(/[^0-9]/g, '').slice(-1);
            box.classList.toggle('filled', box.value !== '');
            setAlert('');
            group.classList.remove('invalid');
            if (box.value && i < boxes.length - 1) focusBox(i + 1);
            if (syncHidden().length === 6) doVerify();
        });
        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !box.value && i > 0) {
                focusBox(i - 1);
                boxes[i - 1].value = '';
                boxes[i - 1].classList.remove('filled');
            } else if (e.key === 'ArrowLeft' && i > 0) {
                focusBox(i - 1);
            } else if (e.key === 'ArrowRight' && i < boxes.length - 1) {
                focusBox(i + 1);
            }
        });
        box.addEventListener('paste', function(e) {
            var text = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (!text) return;
            e.preventDefault();
            text.slice(0, 6).split('').forEach(function(ch, idx) {
                if (boxes[idx]) { boxes[idx].value = ch; boxes[idx].classList.add('filled'); }
            });
            focusBox(Math.min(text.length, boxes.length - 1));
            if (syncHidden().length === 6) doVerify();
        });
    });

    function sendCode() {
        var login = loginInput.value.trim();
        if (!login) { setAlert('Please enter your email or phone number.'); return; }
        setAlert('');
        sendBtn.disabled = true;
        sendBtn.textContent = 'Sending…';

        post('{{ route('loginOtpSend') }}', { login: login }).then(function(res) {
            sendBtn.disabled = false;
            sendBtn.textContent = 'Send Code';
            if (res.status === 'sent') {
                sentTo.textContent = res.message.replace('We sent a 6-digit code to ', '');
                stepIdentify.style.display = 'none';
                stepVerify.style.display = 'flex';
                resetBoxes(true);
                resendAt = res.resendAt || 0;
                tickResend();
            } else {
                setAlert(res.message || 'Something went wrong. Please try again.');
                if (res.resendAt) { resendAt = res.resendAt; }
            }
        }).catch(function() {
            sendBtn.disabled = false;
            sendBtn.textContent = 'Send Code';
            setAlert('Could not reach the server. Please check your connection and try again.');
        });
    }

    sendBtn.addEventListener('click', sendCode);
    loginInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); sendCode(); } });
    verifyBtn.addEventListener('click', doVerify);

    resendBtn.addEventListener('click', function() {
        resendBtn.disabled = true;
        resendBtn.textContent = 'Sending…';
        setAlert('');
        resetBoxes(false);

        post('{{ route('loginOtpSend') }}', { login: loginInput.value.trim() }).then(function(res) {
            if (res.status === 'sent') {
                setAlert(res.message, true);
                focusBox(0);
            } else {
                setAlert(res.message || 'Could not resend right now.');
            }
            resendAt = res.resendAt || (Math.floor(Date.now() / 1000) + 45);
            tickResend();
        }).catch(function() {
            setAlert('Could not reach the server. Please try again.');
            resendBtn.disabled = false;
            resendBtn.textContent = 'Resend code';
        });
    });

    backBtn.addEventListener('click', function() {
        stepVerify.style.display = 'none';
        stepIdentify.style.display = 'block';
        setAlert('');
        clearTimeout(resendTimer);
        resetBoxes(false);
    });
})();
</script>
@endif
@endpush