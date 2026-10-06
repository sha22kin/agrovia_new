@extends(welcomeTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Verify Reset Code')}}</title>
@endsection
@section('SEO')
<meta name="description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta property="og:title" content="{{websiteTitle('Verify Reset Code')}}" />
<link rel="canonical" href="{{route('resetPassword')}}">
@endsection
@push('css')
<style>
    :root {
        --green-900: #123a1a;
        --green-700: #276e33;
        --green-600: #2f8a3d;
        --green-500: #3fae4d;
        --green-400: #5cc768;
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
        --radius-md: 16px;
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
        background: linear-gradient(90deg, var(--green-400), var(--green-600) 45%, var(--green-900));
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
    .icon-ring .icon.is-success {
        color: #fff;
        background: linear-gradient(150deg, var(--green-500), var(--green-700));
    }

    .card h2 { margin: 0 0 8px; font-size: 22px; letter-spacing: -.2px; }
    .card p.lead { margin: 0 0 28px; color: var(--muted); font-size: 14px; line-height: 1.55; }
    .card p.lead strong { color: var(--ink); font-weight: 700; }

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

    .otp-group {
        display: flex;
        justify-content: center;
        gap: clamp(6px, 2.4vw, 12px);
    }
    .otp-box {
        width: clamp(40px, 13vw, 54px);
        height: clamp(48px, 15vw, 60px);
        font-size: clamp(20px, 5.6vw, 24px);
        text-align: center;
        font-weight: 700;
        color: var(--green-900);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--green-50);
        outline: none;
        caret-color: var(--green-600);
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease, transform .15s ease;
    }
    .otp-box:focus {
        border-color: var(--green-500);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(63, 174, 77, 0.16);
        transform: translateY(-1px);
    }
    .otp-box.filled { border-color: var(--green-400); background: var(--white); }
    .otp-group.invalid .otp-box { border-color: var(--danger); background: #fff8f7; }
    .otp-group.shake .otp-box { animation: shake .38s ease; }
    .otp-group.success .otp-box { border-color: var(--green-500); background: var(--green-100); color: var(--green-700); }

    .field-error {
        display: block;
        font-size: 12.5px;
        color: #c0392b;
        margin-top: 10px;
        min-height: 1px;
    }
    .field-error:empty { display: none; }

    .btn-primary {
        width: 100%;
        margin-top: 24px;
        border: none;
        border-radius: 999px;
        padding: 15px 0;
        background: linear-gradient(135deg, var(--green-600), var(--green-500));
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: .1px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease, background .3s ease;
        box-shadow: 0 10px 22px -6px rgba(47, 138, 61, 0.5);
    }
    .btn-primary:hover:not([disabled]) { transform: translateY(-2px); box-shadow: 0 14px 26px -6px rgba(47, 138, 61, 0.55); }
    .btn-primary:active:not([disabled]) { transform: translateY(0); }
    .btn-primary[disabled] { opacity: .65; cursor: not-allowed; transform: none; }
    .btn-primary.is-success { background: linear-gradient(135deg, var(--green-700), var(--green-900)); }
    .btn-primary .spinner {
        width: 15px; height: 15px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,.45);
        border-top-color: #fff;
        animation: spin .7s linear infinite;
        display: none;
    }
    .btn-primary.is-busy .spinner { display: inline-block; }

    .divider-row {
        margin-top: 26px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .divider-row::before, .divider-row::after {
        content: "";
        flex: 1;
        height: 1px;
        background: var(--border);
    }
    .divider-row span { font-size: 12px; color: var(--muted); white-space: nowrap; }

    .resend-btn {
        margin-top: 16px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1.5px solid var(--border);
        background: var(--white);
        color: var(--green-700);
        font-weight: 700;
        font-size: 13.5px;
        padding: 9px 18px;
        border-radius: 999px;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease, color .2s ease, transform .2s ease;
    }
    .resend-btn:hover:not(:disabled) { border-color: var(--green-500); background: var(--green-50); transform: translateY(-1px); }
    .resend-btn:disabled { color: var(--muted); border-color: var(--border); cursor: not-allowed; background: var(--green-50); }
    .resend-btn svg { width: 14px; height: 14px; }
    .resend-btn.is-sending svg { animation: spin .8s linear infinite; }

    .cancel-row { margin-top: 18px; font-size: 13px; }
    .cancel-row a {
        color: var(--muted);
        text-decoration: none;
        border-bottom: 1px dashed var(--border);
        padding-bottom: 1px;
        transition: color .2s ease, border-color .2s ease;
    }
    .cancel-row a:hover { color: var(--green-700); border-color: var(--green-500); }

    @keyframes fadeInUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pulseRing { 0%, 100% { opacity: .55; transform: scale(.92); } 50% { opacity: 1; transform: scale(1.08); } }
    @keyframes shake {
        10%, 90% { transform: translateX(-1px); } 20%, 80% { transform: translateX(2px); }
        30%, 50%, 70% { transform: translateX(-4px); } 40%, 60% { transform: translateX(4px); }
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) {
        .page-container, .form-alert, .otp-group.shake .otp-box, .icon-ring::before { animation: none; }
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
                    <path d="M2 6h20v12H2z" /><path d="M22 6l-10 7L2 6" />
                </svg>
            </div>
        </div>
        <h2>Verify your code</h2>
        <p class="lead">We sent a 6-digit code to <strong>{{ $user->maskedEmail() }}</strong><br>Enter it below to continue resetting your password.</p>

        <form id="verifyForm" action="{{ route('resetPasswordCheck') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="code" id="code">
            <div class="form-alert" id="formAlert" role="alert">{{ session('error') }}</div>

            <div class="otp-group" id="otpGroup">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" class="otp-box" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                        autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}" @if ($i === 0) autofocus @endif>
                @endfor
            </div>
            <span class="field-error" id="codeError">{{ $errors->first('code') }}</span>

            <button type="submit" class="btn-primary" id="submitBtn">
                <span class="spinner"></span>
                <span class="label-text">Verify Code</span>
            </button>
        </form>

        <div class="divider-row"><span>Didn't get the code?</span></div>

        <button type="button" class="resend-btn" id="resendBtn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M21 12a9 9 0 1 1-2.64-6.36" /><path d="M21 4v6h-6" />
            </svg>
            <span class="resend-label">Resend code</span>
        </button>

        <div class="cancel-row">
            <a href="{{ route('forgotPassword') }}">Use a different email</a>
        </div>
    </div>

</div>
@endsection
@push('js')
<script>
    (function() {
        var form = document.getElementById('verifyForm');
        var group = document.getElementById('otpGroup');
        var boxes = Array.prototype.slice.call(group.querySelectorAll('.otp-box'));
        var hidden = document.getElementById('code');
        var codeError = document.getElementById('codeError');
        var alertBox = document.getElementById('formAlert');
        var submitBtn = document.getElementById('submitBtn');
        var submitLabel = submitBtn.querySelector('.label-text');
        var cardIcon = document.getElementById('cardIcon');
        var resendBtn = document.getElementById('resendBtn');
        var resendLabel = resendBtn.querySelector('.resend-label');
        var resendAt = {{ (int) $resendAt }};

        function syncHidden() {
            hidden.value = boxes.map(function(b) { return b.value; }).join('');
        }

        function clearState() {
            alertBox.textContent = '';
            alertBox.classList.remove('success');
            codeError.textContent = '';
            group.classList.remove('invalid', 'success');
        }

        // red border stays until the next edit; the shake is a one-off animation replayed by re-adding its class
        function markInvalid() {
            group.classList.add('invalid');
            group.classList.remove('shake');
            void group.offsetWidth; // force a reflow so the animation restarts even if it just ran
            group.classList.add('shake');
            setTimeout(function() { group.classList.remove('shake'); }, 400);
        }

        function focusBox(i) {
            if (boxes[i]) { boxes[i].focus(); boxes[i].select(); }
        }

        function resetBoxes(focusFirst) {
            boxes.forEach(function(b) { b.value = ''; b.classList.remove('filled'); });
            syncHidden();
            if (focusFirst) focusBox(0);
        }

        function maybeSubmit() {
            syncHidden();
            if (hidden.value.length === 6 && !submitBtn.disabled) {
                form.requestSubmit ? form.requestSubmit() : submitBtn.click();
            }
        }

        boxes.forEach(function(box, i) {
            // clicking/tabbing into a filled box selects its digit, so typing immediately overwrites it
            box.addEventListener('focus', function() { box.select(); });
            box.addEventListener('input', function() {
                box.value = box.value.replace(/[^0-9]/g, '').slice(-1);
                box.classList.toggle('filled', box.value !== '');
                clearState();
                if (box.value && i < boxes.length - 1) focusBox(i + 1);
                maybeSubmit();
            });
            box.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !box.value && i > 0) {
                    focusBox(i - 1);
                    boxes[i - 1].value = '';
                    boxes[i - 1].classList.remove('filled');
                    syncHidden();
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
                var next = Math.min(text.length, boxes.length - 1);
                focusBox(next);
                syncHidden();
                maybeSubmit();
            });
        });

        function csrf() {
            return form.querySelector('[name="_token"]').value;
        }

        function tickResend() {
            var left = resendAt - Math.floor(Date.now() / 1000);
            if (left > 0) {
                resendBtn.disabled = true;
                resendLabel.textContent = 'Resend in ' + left + 's';
                setTimeout(tickResend, 1000);
            } else {
                resendBtn.disabled = false;
                resendLabel.textContent = 'Resend code';
            }
        }
        tickResend();

        function setBusy(busy) {
            submitBtn.disabled = busy;
            submitBtn.classList.toggle('is-busy', busy);
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearState();
            syncHidden();

            if (hidden.value.length < 6) {
                codeError.textContent = 'Please enter all 6 digits.';
                group.classList.add('invalid');
                return;
            }

            setBusy(true);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf()
                },
                body: new FormData(form),
                credentials: 'same-origin'
            }).then(function(res) { return res.json().catch(function() { return {}; }); })
              .then(function(res) {
                if (res.status === 'success') {
                    group.classList.add('success');
                    cardIcon.classList.add('is-success');
                    cardIcon.innerHTML = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>';
                    submitBtn.classList.add('is-success');
                    submitLabel.textContent = 'Verified! Redirecting…';
                    setTimeout(function() { window.location.href = res.redirect; }, 450);
                    return;
                }
                if (res.status === 'errors') {
                    codeError.textContent = (res.datas.code && res.datas.code[0]) || 'Please enter the 6-digit code.';
                    markInvalid();
                } else {
                    alertBox.textContent = res.message || 'Something went wrong. Please try again.';
                    markInvalid();
                    if (res.expired) resetBoxes(true);
                }
                setBusy(false);
            }).catch(function() {
                alertBox.textContent = 'Could not reach the server. Please check your connection and try again.';
                setBusy(false);
            });
        });

        resendBtn.addEventListener('click', function() {
            resendBtn.disabled = true;
            resendBtn.classList.add('is-sending');
            resendLabel.textContent = 'Sending…';
            resetBoxes(false);
            clearState();

            fetch('{{ route('resetPasswordResend') }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf()
                },
                credentials: 'same-origin'
            }).then(function(res) { return res.json().catch(function() { return {}; }); })
              .then(function(res) {
                resendBtn.classList.remove('is-sending');
                alertBox.classList.toggle('success', res.status === 'success');
                alertBox.textContent = res.message || '';
                if (res.status === 'success') focusBox(0);
                if (res.resendAt) {
                    resendAt = res.resendAt;
                    tickResend();
                } else {
                    resendBtn.disabled = false;
                    resendLabel.textContent = 'Resend code';
                }
            }).catch(function() {
                resendBtn.classList.remove('is-sending');
                alertBox.textContent = 'Could not reach the server. Please try again.';
                resendBtn.disabled = false;
                resendLabel.textContent = 'Resend code';
            });
        });
    })();
</script>
@endpush
