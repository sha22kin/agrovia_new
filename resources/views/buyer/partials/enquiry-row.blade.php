{{-- One enquiry. Needs $enquiry (loaded withCount('quotes')). --}}
@php [$statusText,$statusClass] = $enquiry->statusInfo(); @endphp
<a href="{{route('buyer.enquiriesAction',['view',$enquiry->id])}}" class="enquiry-row mb-2 text-decoration-none text-reset">
  <span class="enquiry-ic"><i class="fa-solid fa-list-check"></i></span>
  <div class="flex-grow-1">
    <div class="fw-semibold" style="font-size:.87rem;">{{$enquiry->title}}</div>
    <div class="text-muted" style="font-size:.78rem;">
      @if($enquiry->quantityLabel())Quantity: {{$enquiry->quantityLabel()}}@else{{$enquiry->category?->name ?: 'Enquiry #'.$enquiry->id}}@endif
    </div>
  </div>
  <div class="text-end">
    <div class="fw-semibold {{$statusClass}}" style="font-size:.8rem;">{{$statusText}}</div>
    <div class="order-date">{{$enquiry->created_at?->format('M d, Y')}}</div>
  </div>
  <i class="fa-solid fa-chevron-right text-muted"></i>
</a>
