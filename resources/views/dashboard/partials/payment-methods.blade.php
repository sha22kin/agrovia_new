{{-- Shared by the buyer and seller dashboards (rendered by dashboard/page.blade.php). Needs $role. --}}
@php
  $mobile = $methods->filter(fn ($m) => !$m->isBank());
  $banks = $methods->filter(fn ($m) => $m->isBank());
  // What the form shows: what was just typed, else the method being edited.
  $formType = old('type', $editing?->type ?: 'bKash');
  $formId = old('payment_id', $editing?->id);
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">Payment Methods</h5>
</div>
<p class="text-secondary" style="font-size:.85rem;">{{$role == 'expert' ? 'Add and manage the accounts where you receive your earnings.' : ($role == 'seller' ? 'Add and manage the accounts where you receive payments for your orders.' : 'Add and manage how you pay sellers and settle order transactions.')}}</p>

@foreach(['Mobile Banking'=>[$mobile,'fa-mobile-screen-button','No mobile banking account added yet.'],'Bank Transfer'=>[$banks,'fa-building-columns','No bank account added yet.']] as $heading=>[$list,$icon,$emptyText])
<div class="av-card {{$loop->first?'mt-0':''}}">
  <div class="av-section-title"><i class="fa-solid {{$icon}}"></i> {{$heading}}</div>
  @forelse($list as $method)
  @php [$mIcon,$mBg,$mColor] = $method->look(); @endphp
  <div class="av-pay-card {{$loop->last?'mb-1':'mb-3'}}">
    <span class="av-pay-ic" style="background:{{$mBg}};color:{{$mColor}};"><i class="fa-solid {{$mIcon}}"></i></span>
    <div class="flex-grow-1 overflow-hidden">
      <div class="fw-semibold" style="font-size:.87rem;">{{$method->title()}} @if($method->is_default)<span class="badge-soft-green ms-2">Default</span>@endif</div>
      <div class="text-muted" style="font-size:.76rem;">{{$method->subline()}}</div>
    </div>
    <a href="{{route($role.'.paymentMethods',['edit'=>$method->id]).'#paymentForm'}}" class="btn-av-outline btn-sm">Edit</a>
    @unless($method->is_default)
    <form action="{{route($role.'.paymentMethodAction',['default',$method->id])}}" method="POST" class="ms-2">@csrf<button type="submit" class="btn-av-outline btn-sm">Set Default</button></form>
    @endunless
    <form action="{{route($role.'.paymentMethodAction',['delete',$method->id])}}" method="POST" class="ms-2" data-confirm="Remove this payment method?" data-confirm-ok="Yes, remove" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Remove"><i class="fa-solid fa-trash"></i></button></form>
  </div>
  @empty
  <div class="text-muted" style="font-size:.84rem;">{{$emptyText}}</div>
  @endforelse
</div>
@endforeach

<div class="av-card" id="paymentForm">
  <div class="d-flex justify-content-between align-items-center">
    <div class="av-section-title mb-0"><i class="fa-solid {{$formId?'fa-pen':'fa-plus'}}"></i> {{$formId?'Edit Payment Method':'Add New Payment Method'}}</div>
    @if($formId)<a href="{{route($role.'.paymentMethods')}}" class="av-link-more">Cancel</a>@endif
  </div>
  @if($methods->count() >= \App\Models\PaymentMethod::LIMIT && !$formId)
  <div class="text-muted mt-2" style="font-size:.84rem;">You have added the most payment methods allowed ({{\App\Models\PaymentMethod::LIMIT}}). Remove one to add another.</div>
  @else
  <form method="POST" action="{{route($role.'.paymentMethods')}}">
    @csrf
    <input type="hidden" name="payment_id" value="{{$formId}}">
    <div class="row g-3 mt-1">
      <div class="col-md-6">
        <label class="av-form-label">Method Type</label>
        <select class="form-select @error('type') is-invalid @enderror" name="type" id="payType">
          @foreach(\App\Models\PaymentMethod::TYPES as $type)
          <option value="{{$type}}" {{$formType==$type?'selected':''}}>{{$type}}</option>
          @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="av-form-label">Account Number</label>
        <input type="text" class="form-control @error('account_number') is-invalid @enderror" name="account_number" value="{{old('account_number',$editing?->account_number)}}" maxlength="40" placeholder="017XXXXXXXX" autocomplete="off" required>
        @error('account_number')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6 pay-mobile">
        <label class="av-form-label">Account Type</label>
        <select class="form-select @error('account_kind') is-invalid @enderror" name="account_kind">
          @foreach(\App\Models\PaymentMethod::KINDS as $kind)
          <option value="{{$kind}}" {{old('account_kind',$editing?->account_kind ?: 'Personal')==$kind?'selected':''}}>{{$kind}}</option>
          @endforeach
        </select>
        @error('account_kind')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6 pay-bank">
        <label class="av-form-label">Bank Name</label>
        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" name="bank_name" value="{{old('bank_name',$editing?->bank_name)}}" maxlength="80" placeholder="e.g. Dutch-Bangla Bank">
        @error('bank_name')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6 pay-bank">
        <label class="av-form-label">Branch</label>
        <input type="text" class="form-control @error('branch') is-invalid @enderror" name="branch" value="{{old('branch',$editing?->branch)}}" maxlength="80" placeholder="e.g. Bogura Branch">
        @error('branch')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
    </div>
    <div class="text-muted mt-2" style="font-size:.74rem;"><i class="fa-solid fa-lock me-1"></i>Enter the account number only. Never share a PIN, OTP or card CVV.</div>
    <button type="submit" class="btn-av-primary mt-3"><i class="fa-solid {{$formId?'fa-floppy-disk':'fa-plus'}} me-2"></i>{{$formId?'Save Changes':'Add Method'}}</button>
  </form>
  @endif
</div>


@push('js')
<script>
  (function () {
    var type = document.getElementById('payType');
    if (!type) { return; }
    var number = document.querySelector('[name="account_number"]');

    // Bank fields for a bank account, the account type for mobile banking.
    function sync() {
      var bank = type.value === 'Bank Transfer';
      document.querySelectorAll('.pay-bank').forEach(function (el) { el.classList.toggle('d-none', !bank); });
      document.querySelectorAll('.pay-mobile').forEach(function (el) { el.classList.toggle('d-none', bank); });
      number.placeholder = bank ? 'Account number' : '017XXXXXXXX';
    }
    type.addEventListener('change', sync);
    sync();
  })();
</script>
@endpush
