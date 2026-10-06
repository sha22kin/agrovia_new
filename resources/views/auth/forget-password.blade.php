@extends(welcomeTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Forgot Password')}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{websiteTitle('Forgot Password')}}" />
<meta property="og:description" content="{!!general()->meta_description!!}" />
<meta property="og:image" content="{!!general()->meta_description!!}" />
<meta property="og:url" content="{{route('forgotPassword')}}" />
<link rel="canonical" href="{{route('forgotPassword')}}" />
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
    }

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
    .input-wrap > svg {
        position: absolute; left: 12px;
        width: 16px; height: 16px;
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
    .field.invalid .input-wrap input { border-color: #e0563f; background: #fff8f7; }
    .field-error { font-size: 12px; color: #c0392b; margin-top: 2px; }
    .field-error:empty { display: none; }

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
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        box-shadow: 0 10px 22px -6px rgba(47, 138, 61, 0.5);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 26px -6px rgba(47, 138, 61, 0.55); }

    .back-row { margin-top: 22px; font-size: 13.5px; color: var(--muted); }
    .back-row a { color: var(--green-600); font-weight: 700; text-decoration: none; }
    .back-row a:hover { text-decoration: underline; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    @keyframes pulseRing {
        0%, 100% { opacity: .55; transform: scale(.92); }
        50% { opacity: 1; transform: scale(1.08); }
    }
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
            <div class="icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="10" rx="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
            </div>
        </div>
        <h2>Forgot your password?</h2>
        <p class="lead">Enter the email or mobile number on your account and we'll help you get back in.</p>

        <div class="form-alert {{ session('success') ? 'success' : '' }}" id="formAlert" role="alert">{{ $errors->first('emailormobile') ?: (session('error') ?: session('success')) }}</div>

        <form method="POST" action="{{ route('forgotPassword') }}" id="forgotForm" novalidate>
            @csrf
            <div class="field {{ $errors->has('emailormobile') ? 'invalid' : '' }}">
                <label for="emailormobile">Email or Mobile Number</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16v16H4z" opacity="0" />
                        <path d="M22 6l-10 7L2 6" />
                        <path d="M2 6h20v12H2z" />
                    </svg>
                    <input type="text" id="emailormobile" name="emailormobile" value="{{ old('emailormobile') }}"
                        placeholder="Enter your email or mobile number" autocomplete="username" autofocus required>
                </div>
                <span class="field-error" data-error="emailormobile">{{ $errors->first('emailormobile') }}</span>
            </div>

            <button type="submit" class="btn-primary" id="forgotSubmitBtn">
                <span class="spinner" style="width:15px;height:15px;border-radius:50%;border:2px solid rgba(255,255,255,.45);border-top-color:#fff;animation:spin .7s linear infinite;display:none;margin-right:8px;vertical-align:-2px;"></span>
                <span class="label">Send Reset Instructions</span>
            </button>
        </form>

        <p class="back-row">Remembered your password? <a href="{{ route('login') }}">Back to Login</a></p>
    </div>

</div>
@endsection
@push('js')
<script>
(function() {
    var form = document.getElementById('forgotForm');
    var alertBox = document.getElementById('formAlert');
    var submitBtn = document.getElementById('forgotSubmitBtn');
    var input = document.getElementById('emailormobile');
    var field = input.closest('.field');
    var errorSpan = field.querySelector('[data-error]');

    function setError(message) {
        errorSpan.textContent = message || '';
        field.classList.toggle('invalid', !!message);
    }
    input.addEventListener('input', function() { setError(''); alertBox.textContent = ''; });

    function busy(on) {
        submitBtn.disabled = on;
        submitBtn.querySelector('.spinner').style.display = on ? 'inline-block' : 'none';
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alertBox.textContent = '';
        alertBox.classList.remove('success');
        setError('');

        if (!input.value.trim()) {
            setError('Please enter your email, phone number or name.');
            input.focus();
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
            if (res.status === 'sent') {
                submitBtn.querySelector('.label').textContent = 'Code sent! Redirecting…';
                window.location.href = res.redirect;
                return;
            }
            if (res.status === 'errors') {
                setError((res.datas.emailormobile && res.datas.emailormobile[0]) || 'Please check what you entered.');
                input.focus();
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
