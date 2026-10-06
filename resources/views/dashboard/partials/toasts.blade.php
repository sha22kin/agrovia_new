{{-- Notifications shared by the buyer and seller dashboards (SweetAlert2).
     - Flash messages set by the controllers (success, error, warning, info) and validation errors show as toasts at the top right.
     - window.avToast(type, message) shows a toast from any script (type: success, error, warning, info).
     - A form with data-confirm="text" asks first: data-confirm-ok="Yes, delete" sets the button text, data-confirm-danger makes it red. --}}
@php
  $toasts = [];
  foreach (['success', 'error', 'warning', 'info'] as $type) {
    if (session($type)) {
      $toasts[] = ['type' => $type, 'message' => (string) session($type)];
    }
  }
  if (session('status')) {
    $toasts[] = ['type' => 'info', 'message' => (string) session('status')];
  }
  if ($errors->any()) {
    $toasts[] = ['type' => 'error', 'message' => $errors->first().($errors->count() > 1 ? ' (+'.($errors->count() - 1).' more)' : '')];
  }
@endphp
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  (function () {
    var queue = [];
    var showing = false;

    var toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      showCloseButton: true,
      timerProgressBar: true,
      didOpen: function (el) {
        el.addEventListener('mouseenter', Swal.stopTimer);
        el.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    function escapeHtml(text) {
      return String(text).replace(/[&<>"']/g, function (c) {
        return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c];
      });
    }

    // One toast after the other when several arrive together.
    function next() {
      if (showing || !queue.length) { return; }
      var item = queue.shift();
      showing = true;
      toast.fire({
        icon: item.type,
        title: escapeHtml(item.message),
        timer: item.type === 'error' ? 6000 : 3500,
        didClose: function () { showing = false; next(); }
      });
    }

    window.avToast = function (type, message) {
      queue.push({type: type, message: message});
      next();
    };

    // Forms with data-confirm ask before they are sent.
    document.addEventListener('submit', function (event) {
      var form = event.target;
      var message = form.getAttribute && form.getAttribute('data-confirm');
      if (!message || form.dataset.confirmed) { return; }

      event.preventDefault();
      var danger = form.hasAttribute('data-confirm-danger');
      Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        reverseButtons: true,
        focusCancel: danger,
        confirmButtonText: form.getAttribute('data-confirm-ok') || 'Yes, continue',
        cancelButtonText: 'Cancel',
        confirmButtonColor: danger ? '#c0392b' : '#1a7a3c'
      }).then(function (result) {
        if (result.isConfirmed) {
          form.dataset.confirmed = '1';
          HTMLFormElement.prototype.submit.call(form);
        }
      });
    }, true);

    @foreach($toasts as $item)
    window.avToast(@json($item['type']), @json($item['message']));
    @endforeach
  })();
</script>
