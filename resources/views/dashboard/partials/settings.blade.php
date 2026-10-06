{{-- Shared by the dashboards (rendered by dashboard/page.blade.php). Needs $role. An expert gets the short version: password, 3 notification switches and the language. --}}
@php
  $short = $role == 'expert';
  $privacy = \App\Models\UserSetting::privacy($role);
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">{{$short ? 'Account Settings' : 'Settings'}}</h5>
</div>
<p class="text-secondary" style="font-size:.85rem;">{{$short ? 'Manage your account security and notification preferences.' : 'Manage your account security, notifications and preferences.'}}</p>

<div class="row">
  <div class="col-lg-8">

    <div class="av-card mt-0">
      <div class="av-section-title"><i class="fa-solid fa-lock"></i> Change Password</div>
      <p class="text-secondary mb-3" style="font-size:.85rem;">For your security, passwords are changed by email verification rather than typing your old one here. You'll be signed out, asked to verify a 6-digit code sent to your email, then choose a new password and sign back in.</p>
      <form method="POST" action="{{route($role.'.changePassword')}}"
        data-confirm="You'll be signed out and a 6-digit code will be emailed to you to verify it's really you."
        data-confirm-ok="Yes, log me out" data-confirm-danger>
        @csrf
        <button type="submit" class="btn-av-primary"><i class="fa-solid fa-key me-2"></i>Reset Password</button>
      </form>
    </div>

    <div class="av-card">
      <div class="av-section-title"><i class="fa-solid fa-bell"></i> {{$short ? 'Notifications' : 'Notification Preferences'}}</div>
      @foreach(\App\Models\UserSetting::notifications($role) as $key => [$label,$desc,$default])
      <div class="av-setting-row">
        <div>
          <div class="av-setting-label">{{$label}}</div>
          <div class="av-setting-desc">{{$desc}}</div>
        </div>
        <div class="form-check form-switch ms-auto">
          <input class="form-check-input" type="checkbox" role="switch" data-setting="{{$key}}" aria-label="{{$label}}" {{$setting->$key?'checked':''}}>
        </div>
      </div>
      @endforeach
    </div>

    @if(count($privacy))
    <div class="av-card">
      <div class="av-section-title"><i class="fa-solid fa-eye"></i> Privacy</div>
      @foreach($privacy as $key => [$label,$desc,$default])
      <div class="av-setting-row">
        <div>
          <div class="av-setting-label">{{$label}}</div>
          <div class="av-setting-desc">{{$desc}}</div>
        </div>
        <div class="form-check form-switch ms-auto">
          <input class="form-check-input" type="checkbox" role="switch" data-setting="{{$key}}" aria-label="{{$label}}" {{$setting->$key?'checked':''}}>
        </div>
      </div>
      @endforeach
    </div>
    @endif

    @unless($short)
    <div class="av-card">
      <div class="av-section-title" style="color:#c8362b;"><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</div>
      <div class="av-danger-zone d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <div class="fw-semibold" style="font-size:.85rem;">Deactivate Account</div>
          <div class="text-muted" style="font-size:.78rem;">Temporarily hide your profile and pause activity. Log in again to turn it back on.</div>
        </div>
        <button type="button" class="btn-av-danger" data-bs-toggle="modal" data-bs-target="#deactivateModal">Deactivate</button>
      </div>
    </div>
    @endunless

  </div>

  <div class="col-lg-4">
    <div class="av-mini-card mt-0">
      <div class="av-section-title"><i class="fa-solid fa-language"></i> {{$short ? 'Language' : 'Language & Region'}}</div>
      <form method="POST" action="{{route($role.'.settingsPreferences')}}">
        @csrf
        <label class="av-form-label">Display Language</label>
        <select class="form-select {{$short ? '' : 'mb-3'}} @error('language') is-invalid @enderror" name="language">
          @foreach(\App\Models\UserSetting::LANGUAGES as $code => $name)
          <option value="{{$code}}" {{old('language',$setting->language)==$code?'selected':''}}>{{$name}}</option>
          @endforeach
        </select>
        @unless($short)
        <label class="av-form-label">Currency</label>
        <select class="form-select @error('currency') is-invalid @enderror" name="currency">
          @foreach(\App\Models\UserSetting::CURRENCIES as $code => $name)
          <option value="{{$code}}" {{old('currency',$setting->currency)==$code?'selected':''}}>{{$name}}</option>
          @endforeach
        </select>
        @endunless
        <button type="submit" class="btn-av-primary w-100 mt-3">Save Preferences</button>
      </form>
    </div>
  </div>
</div>

@unless($short)
<!-- Deactivate account -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" action="{{route($role.'.deactivate')}}">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Deactivate your account?</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-secondary" style="font-size:.85rem;">{{$role == 'seller' ? 'Your ads, community posts and comments are hidden and you are logged out.' : 'Your community posts and comments are hidden and you are logged out.'}} Log in again whenever you want and everything comes back.</p>
          <label class="av-form-label">Enter your password to continue</label>
          <input type="password" class="form-control @error('deactivate_password') is-invalid @enderror" name="deactivate_password" autocomplete="current-password" required>
          @error('deactivate_password')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-av-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-av-danger">Deactivate</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endunless

@push('js')
<script>
  (function () {
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Every switch is saved as soon as it is moved; it goes back if the save fails.
    document.querySelectorAll('[data-setting]').forEach(function (box) {
      box.addEventListener('change', function () {
        var value = box.checked;
        var label = box.getAttribute('aria-label');
        box.disabled = true;
        fetch(@json(route($role.'.settingsToggle')), {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json'},
          body: JSON.stringify({key: box.dataset.setting, value: value ? 1 : 0})
        }).then(function (res) {
          if (!res.ok) { throw new Error('failed'); }
          avToast('success', label + ' turned ' + (value ? 'on' : 'off'));
        }).catch(function () {
          box.checked = !value;
          avToast('error', 'Could not save ' + label + '. Please try again');
        }).finally(function () {
          box.disabled = false;
        });
      });
    });

    @if(!$short && $errors->has('deactivate_password'))
    new bootstrap.Modal(document.getElementById('deactivateModal')).show();
    @endif
  })();
</script>
@endpush
