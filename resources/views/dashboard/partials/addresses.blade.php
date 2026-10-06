{{-- Shared by the buyer and seller dashboards (rendered by dashboard/page.blade.php). Needs $role. --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">Addresses</h5>
</div>
<p class="text-secondary" style="font-size:.85rem;">Manage delivery and billing addresses used for your orders.</p>

<div class="row g-3">
  @foreach($addresses as $address)
  <div class="col-md-6 col-xl-4">
    <div class="av-address-card {{$address->is_default?'is-default':''}}">
      @if($address->is_default)<span class="av-default-tag">Default</span>@endif
      <div class="fw-bold mb-1"><i class="fa-solid {{$address->icon()}} me-2 text-success"></i>{{$address->label}}</div>
      <div class="text-secondary" style="font-size:.83rem;">{{$address->fullLine()}}</div>
      @if($address->phone)
      <div class="text-muted mt-2" style="font-size:.8rem;"><i class="fa-solid fa-phone me-1"></i>{{$address->phone}}</div>
      @endif
      <div class="d-flex gap-2 mt-3">
        <button type="button" class="btn-av-outline btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#addressModal"
          data-id="{{$address->id}}" data-label="{{$address->label}}" data-address="{{$address->address}}" data-phone="{{$address->phone}}" data-district="{{$address->district_id}}"><i class="fa-solid fa-pen me-1"></i>Edit</button>
        @unless($address->is_default)
        <form action="{{route($role.'.addressAction',['default',$address->id])}}" method="POST">@csrf<button type="submit" class="btn-av-outline btn-sm py-1 px-2">Set Default</button></form>
        @endunless
        <form action="{{route($role.'.addressAction',['delete',$address->id])}}" method="POST" data-confirm="Remove this address?" data-confirm-ok="Yes, remove" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Remove"><i class="fa-solid fa-trash"></i>@if($address->is_default) Remove @endif</button></form>
      </div>
    </div>
  </div>
  @endforeach

  @if($addresses->count() < \App\Models\Address::LIMIT)
  <div class="col-md-6 col-xl-4">
    <div class="av-add-card" role="button" data-bs-toggle="modal" data-bs-target="#addressModal" data-new="1">
      <i class="fa-solid fa-circle-plus"></i>
      <div class="fw-semibold" style="font-size:.85rem;">Add New Address</div>
    </div>
  </div>
  @endif
</div>

@if($addresses->isEmpty())
<div class="av-empty"><i class="fa-solid fa-location-dot"></i>No saved addresses yet. Add one to use it for your orders.</div>
@endif

<!-- Add / edit address -->
<div class="modal fade" id="addressModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" action="{{route($role.'.addressAction','save')}}">
        @csrf
        <input type="hidden" name="address_id" id="addressId" value="{{old('address_id')}}">
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold" id="addressModalTitle">{{old('address_id')?'Edit Address':'Add New Address'}}</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="av-form-label">Label</label>
              <input type="text" class="form-control @error('label') is-invalid @enderror" name="label" id="addressLabel" value="{{old('label')}}" maxlength="60" placeholder="e.g. Warehouse, Home, Billing" required>
              @error('label')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
            <div class="col-12">
              <label class="av-form-label">Full Address</label>
              <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="addressText" rows="2" maxlength="300" placeholder="House, road, area, district" required>{{old('address')}}</textarea>
              @error('address')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="av-form-label">Phone Number</label>
              <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="addressPhone" value="{{old('phone',old('label')?'':$user->mobile)}}" maxlength="30" placeholder="+880">
              @error('phone')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="av-form-label">District</label>
              <select class="form-select @error('district') is-invalid @enderror" name="district" id="addressDistrict">
                <option value="">Select district</option>
                @foreach($districts as $district)
                <option value="{{$district->id}}" {{old('district')==$district->id?'selected':''}}>{{$district->name}}</option>
                @endforeach
              </select>
              @error('district')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-av-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-av-primary">Save Address</button>
        </div>
      </form>
    </div>
  </div>
</div>


@push('js')
<script>
  (function () {
    var modalEl = document.getElementById('addressModal');
    var defaultPhone = @json($user->mobile);

    // "Edit" fills the form with the address, "Add New Address" starts a blank one.
    modalEl.addEventListener('show.bs.modal', function (event) {
      var btn = event.relatedTarget;
      if (!btn) { return; }
      var edit = btn.hasAttribute('data-id');
      document.getElementById('addressModalTitle').textContent = edit ? 'Edit Address' : 'Add New Address';
      document.getElementById('addressId').value = edit ? btn.dataset.id : '';
      document.getElementById('addressLabel').value = edit ? btn.dataset.label : '';
      document.getElementById('addressText').value = edit ? btn.dataset.address : '';
      document.getElementById('addressPhone').value = edit ? (btn.dataset.phone || '') : (defaultPhone || '');
      document.getElementById('addressDistrict').value = edit ? (btn.dataset.district || '') : '';
      modalEl.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
      modalEl.querySelectorAll('.invalid-feedback').forEach(function (el) { el.remove(); });
    });

    @if($errors->any())
    // The form was sent back with errors: show it again with what was typed.
    new bootstrap.Modal(modalEl).show();
    @endif
  })();
</script>
@endpush
