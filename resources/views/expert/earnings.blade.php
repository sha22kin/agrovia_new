@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Earnings')}}</title>
@endsection

@section('contents')
@php $canWithdraw = $balance >= $min && $methods->isNotEmpty(); @endphp
<h5 class="fw-bold mb-1">Earnings</h5>
<p class="text-secondary" style="font-size:.85rem;">Track your income from consultations and paid content.</p>

<div class="row">
  <div class="col-lg-8">
    <div class="av-card mt-0">
      <div class="av-card-title"><i class="fa-solid fa-file-invoice-dollar"></i> Recent Transactions</div>
      @forelse($transactions as $transaction)
      @php
        $counted = $transaction->isCounted();
        $isWithdrawal = $transaction->type == 'withdrawal';
      @endphp
      <div class="av-earn-row {{$counted?'':'text-muted'}}">
        <div class="overflow-hidden">
          <div>{{$transaction->description ?: ucfirst($transaction->type)}}
            @if($isWithdrawal)
            <span class="{{['pending'=>'badge-soft-amber','paid'=>'badge-soft-green','rejected'=>'badge-soft-red','cancelled'=>'badge-soft-grey'][$transaction->status] ?? 'badge-soft-grey'}} ms-1">{{ucfirst($transaction->status)}}</span>
            @endif
          </div>
          <div class="text-muted" style="font-size:.72rem;">{{$transaction->created_at?->format('M d, Y')}}</div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="fw-semibold {{!$counted?'text-decoration-line-through':($transaction->isIncome()?'text-success':'text-danger')}}">{{$transaction->signedAmount($currency)}}</span>
          @if($isWithdrawal && $transaction->status == 'pending')
          <form action="{{route('expert.withdrawCancel',$transaction->id)}}" method="POST" data-confirm="Cancel this withdrawal? The money goes back to your balance." data-confirm-ok="Yes, cancel" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Cancel the withdrawal"><i class="fa-solid fa-xmark"></i></button></form>
          @endif
        </div>
      </div>
      @empty
      <div class="av-empty"><i class="fa-solid fa-file-invoice-dollar"></i>No transactions yet. Your income shows up here when you complete a consultation.</div>
      @endforelse

      {{$transactions->links('dashboard.partials.pagination')}}
    </div>
  </div>

  <div class="col-lg-4">
    <div class="av-payout-card mt-0">
      <div style="font-size:.8rem;opacity:.85;">Available Balance</div>
      <div class="fw-bold" style="font-size:1.7rem;">{{$currency}} {{number_format($balance)}}</div>
      @if($pending > 0)<div style="font-size:.74rem;opacity:.85;">{{$currency}} {{number_format($pending)}} is waiting to be paid out</div>@endif
      <button type="button" class="btn btn-post mt-2 w-100" data-bs-toggle="modal" data-bs-target="#withdrawModal" {{$canWithdraw?'':'disabled'}}><i class="fa-solid fa-money-bill-transfer me-2"></i>Withdraw</button>
      @if(!$canWithdraw)
      <div style="font-size:.72rem;opacity:.85;" class="mt-2">
        @if($methods->isEmpty())Add a payout account to withdraw. @else The smallest withdrawal is {{$currency}} {{number_format($min)}}. @endif
      </div>
      @endif
    </div>

    <div class="av-mini-card">
      <div class="av-card-title"><i class="fa-solid fa-chart-line"></i> This Month</div>
      <div class="pref-item"><span class="pref-ic"><i class="fa-solid fa-calendar-check"></i></span><div><div class="pref-label">Consultations</div><div class="pref-value">{{number_format($month['sessions'])}} {{$month['sessions']==1?'session':'sessions'}}</div></div></div>
      <div class="pref-item"><span class="pref-ic"><i class="fa-solid fa-sack-dollar"></i></span><div><div class="pref-label">Total Earned</div><div class="pref-value">{{$currency}} {{number_format($month['earned'])}}</div></div></div>
    </div>

    <div class="text-center mt-3"><a href="{{route('expert.paymentMethods')}}" class="av-link-more"><i class="fa-solid fa-building-columns me-1"></i> Manage payout accounts</a></div>
  </div>
</div>

<!-- Withdraw -->
<div class="modal fade" id="withdrawModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" action="{{route('expert.withdraw')}}">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Withdraw</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="text-muted mb-3" style="font-size:.82rem;">Available: <strong>{{$currency}} {{number_format($balance,2)}}</strong> &middot; smallest withdrawal {{$currency}} {{number_format($min)}}</div>
          <label class="av-form-label">Amount ({{$currency}})</label>
          <input type="number" step="any" min="{{$min}}" max="{{floor($balance)}}" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{old('amount',floor($balance))}}" required>
          @error('amount')<div class="invalid-feedback d-block">{{$message}}</div>@enderror

          <label class="av-form-label mt-3">Pay to</label>
          <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method" required>
            @foreach($methods as $method)
            <option value="{{$method->id}}" {{old('payment_method',$methods->first()?->id)==$method->id?'selected':''}}>{{$method->title()}}@if($method->is_default) (default)@endif</option>
            @endforeach
          </select>
          @error('payment_method')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
          <div class="text-muted mt-3" style="font-size:.74rem;">The amount leaves your balance now. The Agroviea team pays it out to the account above.</div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-av-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-av-primary">Request Withdrawal</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  @if($errors->has('amount') || $errors->has('payment_method'))
  // The request was sent back with a problem: show the form again.
  new bootstrap.Modal(document.getElementById('withdrawModal')).show();
  @endif
</script>
@endpush
