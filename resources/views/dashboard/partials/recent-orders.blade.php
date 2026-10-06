{{-- Recent orders card. Needs $role, $recentOrders, $orderAds (keyed by ad id), $summary; a seller also needs $buyers. A buyer sees the orders placed, a seller the orders received. --}}
@php $cur = $summary['currency']; @endphp
<div class="av-card">
  <div class="d-flex justify-content-between align-items-center">
    <div class="av-card-title"><i class="fa-regular fa-file-lines"></i> {{$role == 'seller' ? 'Recent Orders Received' : 'Recent Orders'}}</div>
    <a href="{{route($role.'.orders')}}" class="av-link-more">View All Orders <i class="fa-solid fa-arrow-right"></i></a>
  </div>
  @forelse($recentOrders as $order)
  @include('dashboard.partials.order-row')
  @empty
  @if($role == 'seller')
  <div class="av-empty"><i class="fa-regular fa-file-lines"></i>No orders yet. Orders placed on your ads show up here. <a href="{{route('seller.listingsAction','create')}}">Post an ad</a></div>
  @else
  <div class="av-empty"><i class="fa-regular fa-file-lines"></i>No orders yet. <a href="{{route('buyer.marketplace')}}">Browse the marketplace</a></div>
  @endif
  @endforelse
</div>
