{{-- One order. Needs $role, $order (Transaction), $orderAds (keyed by ad id), $cur (currency); a seller also needs $buyers (users keyed by id). --}}
@php $ad = $orderAds->get($order->src_id); @endphp
<div class="order-row">
  <img class="order-thumb" src="{{asset($ad ? $ad->fiImage() : 'public/medies/noimage.jpg')}}" alt="{{$ad?->name}}" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'">
  <div class="flex-grow-1">
    <div class="order-title">
      @if($ad && $ad->status=='active')<a href="{{route($role.'.marketplaceView',$ad->id)}}">{{$ad->name}}</a>@else{{$ad?->name ?: 'Order #'.$order->id}}@endif
    </div>
    @if($role == 'seller')
    <div class="order-seller">Buyer: {{$order->billing_name ?: ($buyers->get($order->user_id)?->name ?: '—')}}</div>
    @else
    <div class="order-seller">Seller: {{$ad?->user?->name ?: '—'}}</div>
    @endif
  </div>
  <div class="order-price">{{$cur}} {{number_format($order->amount)}}</div>
  <div class="text-end">
    <span class="badge-status badge-{{strtolower($order->statusGroup() ?: 'unknown')}}">{{$order->statusLabel()}}</span>
    <div class="order-date">{{$order->created_at?->format('M d, Y')}}</div>
  </div>
</div>
