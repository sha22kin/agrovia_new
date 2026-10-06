{{-- The order list with the status pills. Needs $role, $orders (paginator), $orderAds, $summary, $status, $counts; a seller also needs $buyers. --}}
@php $cur = $summary['currency']; @endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">{{$role == 'seller' ? 'Orders Received' : 'My Orders'}}</h5>
</div>
<div class="d-flex gap-2 flex-wrap mb-3">
  @foreach($counts as $name=>$total)
  <a href="{{$name=='All'?route($role.'.orders'):route($role.'.orders',['status'=>$name])}}" class="av-cat-pill {{($status ?: 'All')==$name?'active':''}}">{{$name}} ({{number_format($total)}})</a>
  @endforeach
</div>

<div class="av-card mt-0">
  @forelse($orders as $order)
  @include('dashboard.partials.order-row')
  @empty
  @if($role == 'seller')
  <div class="av-empty"><i class="fa-regular fa-file-lines"></i>No orders found. Orders placed on your ads show up here.</div>
  @else
  <div class="av-empty"><i class="fa-regular fa-file-lines"></i>No orders found. <a href="{{route('buyer.marketplace')}}">Browse the marketplace</a></div>
  @endif
  @endforelse

  {{$orders->links('dashboard.partials.pagination')}}
</div>
