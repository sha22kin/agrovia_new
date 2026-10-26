@extends(welcomeTheme() . 'layouts.app')
@section('title')
    <title>{{ websiteTitle('Register') }}</title>
@endsection
@section('SEO')
    <meta name="description" content="{!! general()->meta_description !!}" />
    <meta name="keywords" content="{{ general()->meta_keyword }}" />
    <meta property="og:title" content="{{ websiteTitle('Register') }}" />
    <meta property="og:description" content="{!! general()->meta_description !!}" />
    <meta property="og:image" content="{!! general()->meta_description !!}" />
    <meta property="og:url" content="{{ route('register') }}" />
    <link rel="canonical" href="{{ route('register') }}">
@endsection
@push('css')
    <style>
        :root {
            --green-900: #1c4a24;
            --green-700: #276e33;
            --green-600: #2f8a3d;
            --green-500: #3fae4d;
            --green-100: #eef7ee;
            --green-50: #f6faf5;
            --ink: #1f2a20;
            --muted: #6b786c;
            --white: #ffffff;
            --border: #e2ebe1;
            --shadow: 0 10px 30px rgba(28, 74, 36, 0.10);
            --radius-lg: 22px;
            --radius-md: 14px;
            --radius-sm: 10px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Roboto, "Noto Sans Bengali", sans-serif;
            background: var(--green-50);
            color: var(--ink);
            min-height: 100vh;

        }

        /* ===== Page container ===== */
        .page-container {
            width: 100%;
            max-width: 1080px;
            display: flex;
            flex-direction: column;
            gap: 22px;
            animation: fadeInUp .6s ease both;
            margin: 30px auto;
        }

        /* ===== Top bar ===== */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 18px 30px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand .leaf {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--green-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green-600);
            transition: transform .3s ease;
        }

        .brand:hover .leaf {
            transform: rotate(-18deg) scale(1.08);
        }

        .brand h1 {
            margin: 0;
            font-size: 20px;
            color: var(--green-700);
        }

        .brand span {
            display: block;
            font-size: 11px;
            color: var(--muted);
            font-weight: 400;
        }

        .topbar-right {
            font-size: 14px;
            color: var(--muted);
        }

        .link {
            color: var(--green-600);
            text-decoration: none;
            font-weight: 700;
            transition: color .2s ease;
        }

        .link:hover {
            color: var(--green-800, #1c4a24);
            text-decoration: underline;
        }

        /* ===== Main grid: 2 columns ===== */
        .main-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            align-items: flex-start;
        }

        .col-left {
            flex: 1 1 560px;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 36px 38px;
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        .col-right {
            flex: 1 1 320px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .headline h2 {
            margin: 0 0 6px;
            font-size: 24px;
        }

        .headline p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        /* ===== Form ===== */
        .form-body {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field label {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
        }

        .field label .req {
            color: #e0563f;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap>svg {
            position: absolute;
            left: 12px;
            width: 16px;
            height: 16px;
            color: var(--muted);
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 36px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            background: var(--green-50);
            outline: none;
            transition: border-color .25s ease, box-shadow .25s ease, background .25s ease;
        }

        .input-wrap input:focus {
            border-color: var(--green-500);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(63, 174, 77, 0.15);
        }

        .input-wrap.has-eye input {
            padding-right: 44px;
        }

        .toggle-eye {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            padding: 0;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted);
            background: none;
            border: none;
            border-radius: 50%;
            display: flex;
            transition: color .2s ease, background .2s ease;
        }

        .toggle-eye:hover,
        .toggle-eye:focus-visible {
            color: var(--green-700);
            background: var(--green-100);
            outline: none;
        }

        .toggle-eye svg {
            width: 16px;
            height: 16px;
        }

        .toggle-eye .eye-off {
            display: none;
        }

        .toggle-eye.is-visible .eye-on {
            display: none;
        }

        .toggle-eye.is-visible .eye-off {
            display: block;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: -8px;
        }

        .btn-primary {
            border: none;
            border-radius: 999px;
            padding: 14px 0;
            background: linear-gradient(135deg, var(--green-600), var(--green-500));
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
            box-shadow: 0 8px 18px rgba(47, 138, 61, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(47, 138, 61, 0.45);
            filter: brightness(1.04);
        }

        .btn-primary svg {
            transition: transform .25s ease;
        }

        .btn-primary:hover svg {
            transform: translateX(4px);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .social-row {
            display: flex;
            gap: 12px;
        }

        .btn-social {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 0;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--white);
            cursor: pointer;
            transition: border-color .2s ease, transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .btn-social:hover {
            border-color: var(--green-500);
            background: var(--green-50);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(28, 74, 36, 0.10);
        }

        .btn-social svg {
            width: 18px;
            height: 18px;
        }

        .terms {
            text-align: center;
            font-size: 12px;
            color: var(--muted);
        }

        /* ===== Right column: promo panel ===== */
        .promo-panel {
            position: relative;
            border-radius: var(--radius-lg);
            overflow: hidden;
            min-height: 230px;
            background:
                linear-gradient(180deg, rgba(20, 50, 20, 0.15) 0%, rgba(10, 30, 10, 0.45) 100%),
                url('{{asset('public/welcome/images/hero-farmer.webp')}}') 70% 30%/cover no-repeat;
            display: flex;
            align-items: flex-end;
            padding: 24px;
        }

        .promo-panel h3 {
            color: var(--white);
            font-size: 22px;
            line-height: 1.35;
            margin: 0;
        }

        .promo-panel h3 em {
            color: #a9e6a0;
            font-style: normal;
        }

        /* ===== Join as card ===== */
        .join-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 24px 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .join-card>h4 {
            margin: 0;
            font-size: 16px;
            color: var(--green-700);
        }

        .role-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: border-color .25s ease, background .25s ease, transform .2s ease, box-shadow .2s ease;
        }

        .role-option:hover {
            border-color: var(--green-500);
            background: var(--green-50);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(28, 74, 36, 0.08);
        }

        .role-option.selected {
            border-color: var(--green-600);
            background: var(--green-100);
        }

        .role-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--green-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green-600);
            flex-shrink: 0;
            transition: transform .25s ease;
        }

        .role-option:hover .role-icon {
            transform: scale(1.1);
        }

        .role-text {
            flex: 1;
        }

        .role-text strong {
            display: block;
            font-size: 13.5px;
        }

        .role-text span {
            font-size: 12px;
            color: var(--muted);
        }

        .radio {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--border);
            flex-shrink: 0;
            position: relative;
            transition: border-color .2s ease;
        }

        .role-option.selected .radio {
            border-color: var(--green-600);
        }

        .role-option.selected .radio::after {
            content: "";
            position: absolute;
            inset: 3px;
            border-radius: 50%;
            background: var(--green-600);
            animation: pop .2s ease both;
        }

        .join-note {
            font-size: 11.5px;
            color: var(--muted);
            text-align: center;
        }

        @keyframes pop {
            from {
                transform: scale(0);
            }

            to {
                transform: scale(1);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== Password strength ===== */
        .strength {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .strength-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .strength-bar span {
            height: 6px;
            border-radius: 999px;
            background: var(--border);
            transition: background .25s ease;
        }

        .strength[data-score="1"] .strength-bar span:nth-child(-n+1) {
            background: #e0563f;
        }

        .strength[data-score="2"] .strength-bar span:nth-child(-n+2) {
            background: #f0a020;
        }

        .strength[data-score="3"] .strength-bar span:nth-child(-n+3) {
            background: #9ac93a;
        }

        .strength[data-score="4"] .strength-bar span:nth-child(-n+4) {
            background: var(--green-600);
        }

        /* ===== Dynamic form states ===== */
        .form-alert {
            padding: 11px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            background: #fdeceb;
            color: #b3382c;
            border: 1px solid #f5c6c1;
        }

        .form-alert:empty {
            display: none;
        }

        .field-error {
            font-size: 12px;
            color: #c0392b;
            margin-top: 5px;
        }

        .field-error:empty {
            display: none;
        }

        .field.invalid .input-wrap input {
            border-color: #e0563f;
            background: #fff8f7;
        }

        .btn-primary[disabled] {
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-primary.loading::after {
            content: "";
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .5);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-option {
            position: relative;
        }

        .role-option:focus-within {
            box-shadow: 0 0 0 4px rgba(63, 174, 77, 0.15);
        }

        a.btn-social {
            text-decoration: none;
        }

        .brand,
        .brand:hover {
            text-decoration: none;
            color: inherit;
        }

        @media (max-width:760px) {
            .col-left {
                padding: 28px 22px;
            }

            .topbar {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('contents')
    @php
        $roles = \App\Models\User::registerRoles();
        $selectedRole = old('role', request('role'));
        $selectedRole = array_key_exists($selectedRole, $roles) ? $selectedRole : array_key_first($roles);
        $socialProviders = \App\Models\User::socialProviders();
        $legalPages = \App\Models\Post::whereIn('type', [0, 1])
            ->where('status', 'active')
            ->whereIn('slug', ['terms-and-conditions', 'privacy-policy'])
            ->pluck('name', 'slug');
        $roleIcons = [
            'seller' => '<path d="M12 2l8 4v6c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6l8-4z" />',
            'buyer' => '<circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" /><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6" />',
            'expert' => '<circle cx="12" cy="8" r="4" /><path d="M4 21v-1a8 8 0 0 1 16 0v1" />',
        ];
        $socialIcons = [
            'google' => '<path fill="#EA4335" d="M12 10.2v3.9h5.5c-.24 1.4-1.7 4.1-5.5 4.1-3.3 0-6-2.7-6-6.1s2.7-6.1 6-6.1c1.9 0 3.2.8 3.9 1.5l2.7-2.6C16.9 3.3 14.7 2.3 12 2.3 6.9 2.3 2.7 6.5 2.7 11.6S6.9 20.9 12 20.9c6.9 0 9.6-4.8 9.6-7.3 0-.5 0-.9-.1-1.3H12z" />',
            'facebook' => '<path fill="#1877F2" d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12" />',
        ];
        $fields = [
            'name' => [
                'label' => 'Full Name',
                'type' => 'text',
                'placeholder' => 'Enter your full name',
                'autocomplete' => 'name',
                'icon' => '<circle cx="12" cy="7" r="4" /><path d="M5 21v-1a7 7 0 0 1 7-7h0a7 7 0 0 1 7 7v1" />',
            ],
            'mobile' => [
                'label' => 'Phone Number',
                'type' => 'tel',
                'placeholder' => '01712 345 678',
                'autocomplete' => 'tel',
                'icon' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.7a2 2 0 0 1-.5 2.1L8 9.7a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.5 2.7.6a2 2 0 0 1 1.7 2z" />',
            ],
            'email' => [
                'label' => 'Email Address',
                'type' => 'email',
                'placeholder' => 'Enter your email address',
                'autocomplete' => 'email',
                'icon' => '<path d="M2 6h20v12H2z" /><path d="M22 6l-10 7L2 6" />',
            ],
        ];
    @endphp
    <div class="page-container">

        <!-- Top bar -->
        <div class="topbar">
            <a href="{{ route('index') }}" class="brand">
                <span class="leaf">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C7 2 3 6 3 11c0 5 4 9 9 9s9-4 9-9c0-1-5-1-9-1s-9 0-9 1" />
                    </svg>
                </span>
                <div>
                    <h1>{{ general()->title }}</h1>
                    <span>{{ general()->subtitle ?: 'Empowering Farmers' }}</span>
                </div>
            </a>
            <div class="topbar-right">Already have an account? <a href="{{ route('login') }}" class="link">Login</a></div>
        </div>

        <!-- Main 2-column grid -->
        <div class="main-grid">

            <!-- Left column: form card -->
            <div class="col-left">
                <div class="headline">
                    <h2>Create Your Account</h2>
                    <p>Join {{ general()->title }} and grow your agricultural business</p>
                </div>

                <!-- Form fields -->
                <form class="form-body" id="registerForm" action="{{ route('register') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="{{ $selectedRole }}">

                    <div class="form-alert" id="formAlert" role="alert">{{ session('error') }}</div>

                    @foreach ($fields as $name => $f)
                        <div class="field {{ $errors->has($name) ? 'invalid' : '' }}">
                            <label for="{{ $name }}">{{ $f['label'] }} <span class="req">*</span></label>
                            <div class="input-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">{!! $f['icon'] !!}</svg>
                                <input type="{{ $f['type'] }}" id="{{ $name }}" name="{{ $name }}"
                                    value="{{ old($name) }}" placeholder="{{ $f['placeholder'] }}"
                                    autocomplete="{{ $f['autocomplete'] }}" required>
                            </div>
                            <span class="field-error" data-error="{{ $name }}">{{ $errors->first($name) }}</span>
                        </div>
                    @endforeach

                    <div class="field {{ $errors->has('password') ? 'invalid' : '' }}">
                        <label for="password">Password <span class="req">*</span></label>
                        <div class="input-wrap has-eye">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="10" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" id="password" name="password" placeholder="Create a strong password"
                                autocomplete="new-password" minlength="6" required>
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

                        <div class="strength" id="strength" data-score="0">
                            <div class="strength-bar">
                                <span></span><span></span><span></span><span></span>
                            </div>
                        </div>
                        <span class="hint mt-1 ms-1">Minimum 6 characters</span>
                    </div>

                    <span class="field-error" data-error="role">{{ $errors->first('role') }}</span>

                    <button type="submit" class="btn-primary" id="submitBtn">
                        <span class="label">Sign Up</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="M13 6l6 6-6 6" />
                        </svg>
                    </button>
                </form>

                @if (count($socialProviders))
                    <div class="divider">or sign up with</div>

                    <div class="social-row">
                        @foreach ($socialProviders as $key => $label)
                            <a class="btn-social" data-social href="{{ route('socialRedirect', $key) }}"
                                data-base="{{ route('socialRedirect', $key) }}" aria-label="Sign up with {{ $label }}"
                                title="{{ $label }}">
                                <svg viewBox="0 0 24 24">{!! $socialIcons[$key] !!}</svg>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if ($legalPages->isNotEmpty())
                    <p class="terms">By signing up, you agree to our
                        @if ($legalPages->has('terms-and-conditions'))
                            <a href="{{ route('pageView', 'terms-and-conditions') }}" class="link" target="_blank">Terms of Service</a>
                        @endif
                        @if ($legalPages->count() == 2)
                            and
                        @endif
                        @if ($legalPages->has('privacy-policy'))
                            <a href="{{ route('pageView', 'privacy-policy') }}" class="link" target="_blank">Privacy Policy</a>
                        @endif
                    </p>
                @endif
            </div>

            <!-- Right column: promo + role selection -->
            <div class="col-right">
                <div class="promo-panel">
                    <h3><em>Empowering Farmers,</em><br>Building a Better<br>Tomorrow</h3>
                </div>

                <div class="join-card">
                    <h4>Join as</h4>

                    @foreach ($roles as $key => [$title, $subtitle])
                        <label class="role-option {{ $selectedRole == $key ? 'selected' : '' }}">
                            <input type="radio" name="role_choice" value="{{ $key }}" {{ $selectedRole == $key ? 'checked' : '' }}>
                            <span class="role-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">{!! $roleIcons[$key] !!}</svg>
                            </span>
                            <div class="role-text">
                                <strong>{{ $title }}</strong>
                                <span>{{ $subtitle }}</span>
                            </div>
                            <span class="radio"></span>
                        </label>
                    @endforeach

                    <p class="join-note">You can change this later in your profile ⓘ</p>
                </div>
            </div>

        </div>

    </div>
@endsection
@push('js')
    @include('auth.partials.password-toggle')
    <script>
        (function() {
            var form = document.getElementById('registerForm');
            var alertBox = document.getElementById('formAlert');
            var btn = document.getElementById('submitBtn');
            var roleInput = document.getElementById('roleInput');
            var fields = ['name', 'mobile', 'email', 'password', 'role'];

            // "Join as" choice: keeps the hidden field and the social sign-up links in step
            var options = document.querySelectorAll('.role-option');
            function applyRole(role) {
                roleInput.value = role;
                options.forEach(function(o) {
                    o.classList.toggle('selected', o.querySelector('input').value === role);
                });
                document.querySelectorAll('[data-social]').forEach(function(a) {
                    a.href = a.dataset.base + '?role=' + encodeURIComponent(role);
                });
            }
            options.forEach(function(o) {
                o.querySelector('input').addEventListener('change', function() { applyRole(this.value); });
            });
            applyRole(roleInput.value);

            // Password strength bar
            var strength = document.getElementById('strength');
            var passwordInput = document.getElementById('password');
            var passwordMessage = 'Password must be at least 6 characters.';

            // 6 characters is enough; the bar fills up while typing and is full at 6
            function passwordScore(value) {
                return value.length >= 6 ? 4 : Math.min(3, Math.ceil(value.length * 4 / 6));
            }
            function renderStrength() {
                var value = passwordInput.value;
                strength.dataset.score = value ? passwordScore(value) : 0;
            }
            passwordInput.addEventListener('input', renderStrength);
            renderStrength();

            function setError(name, message) {
                var box = form.querySelector('[data-error="' + name + '"]');
                if (box) box.textContent = message || '';
                var input = form.querySelector('[name="' + name + '"]');
                if (input && input.closest('.field')) input.closest('.field').classList.toggle('invalid', !!message);
            }
            function clearErrors() {
                alertBox.textContent = '';
                fields.forEach(function(f) { setError(f, ''); });
            }
            function busy(on) {
                btn.disabled = on;
                btn.classList.toggle('loading', on);
            }
            form.querySelectorAll('input').forEach(function(input) {
                input.addEventListener('input', function() { setError(input.name, ''); });
            });

            // Live email check: once the field has been left once, "@" and a dot after it are checked on every keystroke
            var emailInput = document.getElementById('email');
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var emailMessage = 'Please enter a valid email address, e.g. name@example.com';
            var emailTouched = false;
            function validateEmailLive() {
                var value = emailInput.value.trim();
                if (!emailTouched || value === '') return;
                setError('email', emailPattern.test(value) ? '' : emailMessage);
            }
            emailInput.addEventListener('blur', function() { emailTouched = true; validateEmailLive(); });
            emailInput.addEventListener('input', validateEmailLive);

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                clearErrors();

                if (!emailPattern.test(emailInput.value.trim())) {
                    setError('email', emailMessage);
                    emailInput.focus();
                    return;
                }

                if (passwordScore(passwordInput.value) < 4) {
                    setError('password', passwordMessage);
                    passwordInput.focus();
                    return;
                }

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
                }).then(function(res) {
                    return res.json().catch(function() { return {}; });
                }).then(function(res) {
                    if (res.status === 'success') {
                        btn.querySelector('.label').textContent = 'Account created! Redirecting…';
                        window.location.href = res.redirect;
                        return;
                    }
                    if (res.status === 'verify') {
                        btn.querySelector('.label').textContent = 'Code sent! Redirecting…';
                        window.location.href = res.redirect;
                        return;
                    }
                    if (res.status === 'errors') {
                        var first = null;
                        Object.keys(res.datas).forEach(function(k) {
                            setError(k, res.datas[k][0]);
                            first = first || k;
                        });
                        var el = first && form.querySelector('[name="' + first + '"]');
                        if (el && el.type !== 'hidden') el.focus();
                    } else {
                        alertBox.textContent = res.message || 'Something went wrong. Please refresh the page and try again.';
                    }
                    busy(false);
                }).catch(function() {
                    alertBox.textContent = 'Could not reach the server. Please check your connection and try again.';
                    busy(false);
                });
            });
        })();
    </script>
@endpush
