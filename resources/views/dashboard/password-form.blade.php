{{-- Change password form shared by the buyer and seller themes. Needs $action. --}}
<form method="POST" action="{{$action}}">
  @csrf
  <div class="row mb-3">
    <label class="col-sm-3 col-form-label">Current password <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="password" class="form-control" name="current_password" placeholder="Current password" required>
      @if ($errors->has('current_password'))<div class="invalid-feedback d-block">{{ $errors->first('current_password') }}</div>@endif
    </div>
  </div>
  <div class="row mb-3">
    <label class="col-sm-3 col-form-label">New password <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="password" class="form-control" name="password" placeholder="New password" required>
      @if ($errors->has('password'))<div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>@endif
    </div>
  </div>
  <div class="row mb-3">
    <label class="col-sm-3 col-form-label">Confirm password <span class="text-danger">*</span></label>
    <div class="col-sm-9">
      <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required>
    </div>
  </div>
  <button type="submit" class="btn btn-theme">Change Password</button>
</form>
