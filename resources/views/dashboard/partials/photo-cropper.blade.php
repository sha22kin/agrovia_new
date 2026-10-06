{{-- Crop and save the profile photo (Cropper.js). Works with the "Change photo" button and #photoInput of the profile form.
     The cropped square is sent to the profile photo route, which keeps it as a WebP in the media table. --}}
@php $photoRole = request()->routeIs('seller.*') ? 'seller' : (request()->routeIs('expert.*') ? 'expert' : 'buyer'); @endphp
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
@endpush

<div class="modal fade" id="photoModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title fw-bold">Crop your photo</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="av-cropper-box av-cropper-round"><img id="cropperImage" alt=""></div>
        <div class="d-flex justify-content-center gap-2 mt-3">
          <button type="button" class="btn btn-light border btn-sm" data-crop="zoom-out" title="Zoom out"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
          <button type="button" class="btn btn-light border btn-sm" data-crop="zoom-in" title="Zoom in"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
          <button type="button" class="btn btn-light border btn-sm" data-crop="rotate-left" title="Rotate left"><i class="fa-solid fa-rotate-left"></i></button>
          <button type="button" class="btn btn-light border btn-sm" data-crop="rotate-right" title="Rotate right"><i class="fa-solid fa-rotate-right"></i></button>
          <button type="button" class="btn btn-light border btn-sm" data-crop="reset" title="Reset"><i class="fa-solid fa-arrows-rotate"></i></button>
        </div>
        <div class="text-center text-muted mt-2" style="font-size:.74rem;">Drag the photo to move it, scroll to zoom.</div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-theme" id="photoSaveBtn"><i class="fa-solid fa-check me-1"></i> Save Photo</button>
      </div>
    </div>
  </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
  (function () {
    var input = document.getElementById('photoInput');
    var pick = document.getElementById('photoPickBtn');
    var modalEl = document.getElementById('photoModal');
    var image = document.getElementById('cropperImage');
    var saveBtn = document.getElementById('photoSaveBtn');
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var modal = new bootstrap.Modal(modalEl);
    var cropper = null;
    var objectUrl = null;

    pick.addEventListener('click', function () { input.click(); });

    input.addEventListener('change', function () {
      var file = input.files[0];
      if (!file) { return; }
      if (!/^image\/(jpeg|png|gif|webp)$/.test(file.type)) {
        avToast('error', 'Please choose a JPG, PNG, GIF or WebP image');
        input.value = '';
        return;
      }
      if (file.size > 10 * 1024 * 1024) {
        avToast('error', 'The photo is larger than 10 MB');
        input.value = '';
        return;
      }
      if (objectUrl) { URL.revokeObjectURL(objectUrl); }
      objectUrl = URL.createObjectURL(file);
      image.src = objectUrl;
      modal.show();
    });

    // Cropper needs a visible image, so it starts when the modal is open.
    modalEl.addEventListener('shown.bs.modal', function () {
      cropper = new Cropper(image, {aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 1, background: false, toggleDragModeOnDblclick: false});
    });
    modalEl.addEventListener('hidden.bs.modal', function () {
      if (cropper) { cropper.destroy(); cropper = null; }
      input.value = '';
    });

    modalEl.querySelectorAll('[data-crop]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (!cropper) { return; }
        var action = btn.getAttribute('data-crop');
        if (action === 'zoom-in') { cropper.zoom(0.1); }
        if (action === 'zoom-out') { cropper.zoom(-0.1); }
        if (action === 'rotate-left') { cropper.rotate(-90); }
        if (action === 'rotate-right') { cropper.rotate(90); }
        if (action === 'reset') { cropper.reset(); }
      });
    });

    function done() {
      saveBtn.disabled = false;
      saveBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Save Photo';
    }

    saveBtn.addEventListener('click', function () {
      if (!cropper) { return; }
      var canvas = cropper.getCroppedCanvas({width: 600, height: 600, imageSmoothingQuality: 'high'});
      if (!canvas) { avToast('error', 'Could not crop the photo'); return; }

      saveBtn.disabled = true;
      saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

      // WebP where the browser can make it; the server turns anything else into WebP.
      canvas.toBlob(function (blob) {
        if (!blob) { avToast('error', 'Could not crop the photo'); done(); return; }
        var form = new FormData();
        form.append('photo', blob, blob.type === 'image/webp' ? 'profile.webp' : 'profile.png');

        fetch(@json(route($photoRole.'.profilePhoto')), {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json'},
          body: form
        }).then(function (res) {
          return res.json().catch(function () { return {}; }).then(function (data) { return {ok: res.ok, data: data}; });
        }).then(function (result) {
          if (!result.ok) {
            var errors = result.data.errors || {};
            var first = Object.keys(errors).length ? errors[Object.keys(errors)[0]][0] : result.data.message;
            throw new Error(first || 'Could not save the photo');
          }
          document.querySelectorAll('.profile-photo').forEach(function (img) { img.src = result.data.url; });
          document.querySelectorAll('.av-user-chip img').forEach(function (img) { img.src = result.data.url_small; });
          avToast('success', result.data.message);
          modal.hide();
        }).catch(function (error) {
          avToast('error', error.message || 'Could not save the photo');
        }).finally(done);
      }, 'image/webp', 0.9);
    });
  })();
</script>
@endpush
