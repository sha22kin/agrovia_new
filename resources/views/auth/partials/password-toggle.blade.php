{{-- Show / hide password. Works for every ".toggle-eye" button placed inside an ".input-wrap" (login, register). --}}
<script>
    (function() {
        if (window.__passwordToggle) return;
        window.__passwordToggle = true;

        // capture phase + delegation: no other script or replaced element can swallow the click
        document.addEventListener('click', function(e) {
            var btn = e.target.closest ? e.target.closest('.toggle-eye') : null;
            if (!btn) return;

            var input = btn.closest('.input-wrap').querySelector('input');
            if (!input) return;

            e.preventDefault();
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('is-visible', show);
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        }, true);
    })();
</script>
