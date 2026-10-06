{{-- One buyer requirement (enquiry). Needs $enquiry with user, category, quotes_count and the seller's own quote loaded as quotes. --}}
@php
  $myQuote = $enquiry->quotes->first();
  $cur = general()->currency ?: '৳';
@endphp
<a href="{{route('seller.enquiriesAction',['view',$enquiry->id])}}" class="enquiry-row mb-2 text-decoration-none text-reset">
  <span class="enquiry-ic"><i class="bx bx-notepad"></i></span>
  <div class="flex-grow-1 overflow-hidden">
    <div class="enquiry-title text-truncate">{{$enquiry->title}}</div>
    <div class="enquiry-meta text-truncate">
      Buyer: {{$enquiry->user?->name ?: '—'}}@if($enquiry->quantityLabel()) &middot; Quantity: {{$enquiry->quantityLabel()}}@endif @if($enquiry->category) &middot; {{$enquiry->category->name}}@endif
    </div>
  </div>
  <div class="text-end">
    @if($myQuote)
    <div class="enquiry-quote text-success">You quoted {{$cur}} {{number_format($myQuote->price)}}</div>
    @else
    <div class="enquiry-quote text-warning">Open &middot; {{$enquiry->quotes_count}} {{$enquiry->quotes_count==1?'quote':'quotes'}}</div>
    @endif
    <div class="order-date">{{$enquiry->created_at?->format('M d, Y')}}</div>
  </div>
  <i class="bx bx-chevron-right text-muted"></i>
</a>

