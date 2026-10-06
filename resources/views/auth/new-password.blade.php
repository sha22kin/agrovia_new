@extends(welcomeTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Set a New Password')}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{websiteTitle('Set a New Password')}}" />
<link rel="canonical" href="{{route('newPassword')}}">
@endsection
@push('css')
<style>
    :root {
        --green-900: #123a1a;
        --green-700: #276e33;
        --green-600: #2f8a3d;
        --green-500: #3fae4d;
        --green-100: #eef7ee;
        --green-50: #f5faf4;
        --ink: #16211a;
        --muted: #6f7d72;
        --white: #ffffff;
        --border: #e4ebe2;
        --danger: #e0563f;
        --danger-bg: #fdeceb;
        --shadow: 0 20px 45px -15px rgba(20, 60, 30, 0.28);
        --radius-lg: 26px;
        --radius-sm: 12px;
    }

    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }
    body {
        font-family: "Segoe UI", Roboto, "Noto Sans Bengali", sans-serif;
        color: var(--ink);
        min-height: 100vh;
        background:
            radial-gradient(60% 50% at 15% 0%, rgba(63, 174, 77, 0.14), transparent 60%),
            radial-gradient(55% 45% at 100% 15%, rgba(63, 174, 77, 0.10), transparent 55%),
            var(--green-50);
    }

    .page-container {
        width: 100%;
        max-width: 460px;
        display: flex;
        flex-direction: column;
        gap: 26px;
        animation: fadeInUp .55s cubic-bezier(.2,.8,.2,1) both;
        margin: 64px auto;
        padding: 0 16px;
    }

    .brand {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }
    .brand .leaf {
        width: 34px; height: 34px;
        border-radius: 11px;
        background: linear-gradient(145deg, var(--green-500), var(--green-700));
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        box-shadow: 0 8px 16px -6px rgba(47, 138, 61, 0.55);
    }
    .brand h1 { margin: 0; font-size: 20px; color: var(--green-900); letter-spacing: -.2px; }

    .card {
        position: relative;
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        border: 1px solid rgba(28, 74, 36, 0.05);
        padding: 38px 32px 32px;
        text-align: center;
        overflow: hidden;
    }
    .card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 5px;
        background: linear-gradient(90deg, #5cc768, var(--green-600) 45%, var(--green-900));
    }

    .icon-ring {
        position: relative;
        width: 68px; height: 68px;
        margin: 4px auto 20px;
    }
    .icon-ring::before {
        content: "";
        position: absolute; inset: 0;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(63,174,77,0.22), rgba(63,174,77,0) 70%);
        animation: pulseRing 2.6s ease-in-out infinite;
    }
    .icon-ring .icon {
        position: relative;
        width: 100%; height: 100%;
        border-radius: 50%;
        background: linear-gradient(150deg, var(--green-100), #ffffff);
        color: var(--green-600);
        display: flex; align-items: center; justify-content: center;
        box-shadow: inset 0 0 0 1px rgba(47,138,61,0.12), 0 10px 20px -10px rgba(47,138,61,0.4);
        transition: color .3s ease, background .3s ease;
    }
    .icon-ring .icon.is-success { color: #fff; background: linear-gradient(150deg, var(--green-500), var(--green-700)); }

    .card h2 { margin: 0 0 8px; font-size: 22px; letter-spacing: -.2px; }
    .card p.lead { margin: 0 0 26px; color: var(--muted); font-size: 14px; line-height: 1.55; }

    .form-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 11px 14px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        background: var(--danger-bg);
        color: #a3372a;
        border: 1px solid #f5c6c1;
        margin-bottom: 18px;
        text-align: left;
        animation: fadeInUp .25s ease both;
    }
    .form-alert.success { background: var(--green-100); color: var(--green-700); border-color: #cfe8cf; }
    .form-alert:empty { display: none; }

    .field { display: flex; flex-direction: column; gap: 6px; text-align: left; }
    .field label { font-size: 13px; font-weight: 600; color: var(--ink); }
    .input-wrap { position: relative; display: flex; align-items: center; }
    .input-wrap > svg { position: absolute; left: 12px; width: 16px; height: 16px; color: var(--muted); pointer-events: none; }
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
    .input-wrap input:focus { border-color: var(--green-500); background: var(--white); box-shadow: 0 0 0 4px rgba(63, 174, 77, 0.15); }
    .field.invalid .input-wrap input { border-color: var(--danger); background: #fff8f7; }
    .input-wrap.has-eye input { padding-right: 44px; }
    .toggle-eye {
        position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
        width: 32px; height: 32px; padding: 0;
        display: flex; align-items: center; justify-content: center;
        border: none; background: none; border-radius: 50%;
        color: var(--muted); cursor: pointer;
        transition: color .2s ease, background .2s ease;
    }
    .toggle-eye:hover, .toggle-eye:focus-visible { color: var(--green-700); background: var(--green-100); outline: none; }
    .toggle-eye svg { width: 16px; height: 16px; }
    .toggle-eye .eye-off { display: none; }
    .toggle-eye.is-visible .eye-on { display: none; }
    .toggle-eye.is-visible .eye-off { display: block; }

    .field-error { font-size: 12px; color: #c0392b; margin-top: 2px; }
    .field-error:empty { display: none; }

    .strength { display: flex; flex-direction: column; gap: 7px; margin-top: 2px; }
    .strength-bar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
    .strength-bar span { height: 6px; border-radius: 999px; background: var(--border); transition: background .25s ease; }
    .strength[data-score="1"] .strength-bar span:nth-child(-n+1) { background: #e0563f; }
    .strength[data-score="2"] .strength-bar span:nth-child(-n+2) { background: #f0a020; }
    .strength[data-score="3"] .strength-bar span:nth-child(-n+3) { background: #9ac93a; }
    .strength[data-score="4"] .strength-bar span:nth-child(-n+4) { background: var(--green-600); }
    .hint { font-size: 12px; color: var(--muted); margin: 0 0 0 1px; }

    .btn-primary {
        width: 100%;
        margin-top: 20px;
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
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease, background .3s ease;
        box-shadow: 0 10px 22px -6px rgba(47, 138, 61, 0.5);
    }
    .btn-primary:hover:not([disabled]) { transform: translateY(-2px); box-shadow: 0 14px 26px -6px rgba(47, 138, 61, 0.55); }
    .btn-primary[disabled] { opacity: .65; cursor: not-allowed; transform: none; }
    .btn-primary.is-success { background: linear-gradient(135deg, var(--green-700), var(--green-900)); }
    .btn-primary .spinner {
        width: 15px; height: 15px; border-radius: 50%;
        border: 2px solid rgba(255,255,255,.45); border-top-color: #fff;
        animation: spin .7s linear infinite; display: none;
    }
    .btn-primary.is-busy .spinner { display: inline-block; }

    .back-row { margin-top: 22px; font-size: 13.5px; color: var(--muted); }
    .back-row a { color: var(--green-600); font-weight: 700; text-decoration: none; }
    .back-row a:hover { text-decoration: underline; }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pulseRing { 0%, 100% { opacity: .55; transform: scale(.92); } 50% { opacity: 1; transform: scale(1.08); } }
    @keyframes spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) {
        .page-container, .form-alert, .icon-ring::before { animation: none; }
    }
</style>
@endpush

@section('contents')
<div class="page-container">

    <a href="{{ route('index') }}" class="brand">
        <span class="leaf">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C7 2 3 6 3 11c0 5 4 9 9 9s9-4 9-9c0-1-5-1-9-1s-9 0-9 1" />
            </svg>
        </span>
        <h1>{{ general()->title }}</h1>
    </a>

    <div class="card">
        <div class="icon-ring">
            <div class="icon" id="cardIcon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="10" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
            </div>
        </div>
        <h2>Set a new password</h2>
        <p class="lead">Your code is verified. Choose a new password for <strong>{{ $user->name }}</strong>'s account.</p>

        <div class="form-alert" id="formAlert" role="alert">{{ session('error') }}</div>

        <form id="newPasswordForm" action="{{ route('newPassword') }}" method="POST" novalidate>
            @csrf
            <div class="field" id="passwordField">
                <label for="password">New Password</label>
                <div class="input-wrap has-eye">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="10" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    <input type="password" id="password" name="password" placeholder="Create a new password"
                        autocomplete="new-password" minlength="6" autofocus required>
                    <button type="button" class="toggle-eye" aria-label="Show password">
                        <svg class="eye-on" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" /><circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg class="eye-off" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-7-11-7a18.5 18.5 0 0 1 5.06-5.94" />
                            <path d="M9.9 4.24A9.1 9.1 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                            <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24" /><path d="M1 1l22 22" />
                        </svg>
                    </button>
                </div>
                <span class="field-error" id="passwordError">{{ $errors->first('password') }}</span>

                <div class="strength" id="strength" data-score="0">
                    <div class="strength-bar">
                        <span></span><span></span><span></span><span></span>
                    </div>
                </div>
                <span class="hint">Minimum 6 characters</span>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">
                <span class="spinner"></span>
                <span class="label-text">Set New Password</span>
            </button>
        </form>

        <p class="back-row">Changed your mind? <a href="{{ route('login') }}">Back to Login</a></p>
    </div>

</div>
@endsection
@push('js')
@include('auth.partials.password-toggle')
<script>
    (function() {
        var form = document.getElementById('newPasswordForm');
        var alertBox = document.getElementById('formAlert');
        var passwordInput = document.getElementById('password');
        var passwordField = document.getElementById('passwordField');
        var passwordError = document.getElementById('passwordError');
        var submitBtn = document.getElementById('submitBtn');
        var submitLabel = submitBtn.querySelector('.label-text');
        var cardIcon = document.getElementById('cardIcon');
        var strength = document.getElementById('strength');

        function setError(message) {
            passwordError.textContent = message || '';
            passwordField.classList.toggle('invalid', !!message);
        }

        // 6 characters is enough; the bar fills up while typing and is full at 6
        function passwordScore(value) {
            return value.length >= 6 ? 4 : Math.min(3, Math.ceil(value.length * 4 / 6));
        }
        function renderStrength() {
            var value = passwordInput.value;
            strength.dataset.score = value ? passwordScore(value) : 0;
        }
        passwordInput.addEventListener('input', function() { setError(''); alertBox.textContent = ''; renderStrength(); });
        renderStrength();

        function busy(on) {
            submitBtn.disabled = on;
            submitBtn.classList.toggle('is-busy', on);
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alertBox.textContent = '';
            alertBox.classList.remove('success');
            setError('');

            if (passwordInput.value.length < 6) {
                setError('Password must be at least 6 characters.');
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
            }).then(function(res) { return res.json().catch(function() { return {}; }); })
              .then(function(res) {
                if (res.status === 'success') {
                    cardIcon.classList.add('is-success');
                    cardIcon.innerHTML = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>';
                    submitBtn.classList.add('is-success');
                    submitLabel.textContent = 'Password updated! Redirecting…';
                    setTimeout(function() { window.location.href = res.redirect; }, 450);
                    return;
                }
                if (res.status === 'errors') {
                    setError((res.datas.password && res.datas.password[0]) || 'Please check your password.');
                    passwordInput.focus();
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
@endpush
