@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Payments')}}</title>
@endsection

@section('contents')
<h1 class="av-page-title">Payments</h1>
<div class="av-card first mt-2">
  <div class="av-card-title mb-3">Payment list</div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead><tr><th>SL</th><th>Billing name</th><th>Method</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
        @forelse($payments as $i=>$payment)
        <tr>
          <td>{{$payments->firstItem()+$i}}</td>
          <td>{{$payment->billing_name}}</td>
          <td>{{$payment->payment_method}}</td>
          <td>{{priceFullFormat($payment->amount)}}</td>
          <td>{{$payment->status}}</td>
          <td>{{$payment->created_at?$payment->created_at->format('d-m-Y'):''}}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-4">No Payment</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{$payments->links('pagination')}}
</div>
@endsection
