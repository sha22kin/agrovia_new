@extends(buyerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle($enquiry->title)}}</title>
@endsection

@section('contents')
@php
  [$statusText,$statusClass] = $enquiry->statusInfo();
  $cur = general()->currency ?: '৳';
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">Enquiry Details</h5>
  <a href="{{route('buyer.enquiries')}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to My Enquiries</a>
</div>

<div class="av-card mt-0">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
      <div class="fw-bold" style="font-size:1.05rem;">{{$enquiry->title}}</div>
      <div class="text-muted" style="font-size:.8rem;">Posted {{$enquiry->created_at?->format('M d, Y')}}</div>
    </div>
    <div class="fw-semibold {{$statusClass}}">{{$enquiry->isPending() || $enquiry->isRejected() || $enquiry->isClosed() || $enquiry->isExpired() ? $statusText : ($quotes->count().' '.($quotes->count()==1?'Quote':'Quotes').' Received')}}</div>
  </div>

  @if($enquiry->isPending())
  <div class="alert alert-warning mt-3 mb-0 py-2" style="font-size:.85rem;">
    <i class="fa-regular fa-clock me-1"></i> Waiting for admin approval. Sellers will see this enquiry on the RFQ page once it is approved.
  </div>
  @elseif($enquiry->isRejected())
  <div class="alert alert-danger mt-3 mb-0 py-2" style="font-size:.85rem;">
    <b>Not approved:</b> {{$enquiry->reject_reason ?: 'No reason given.'}}
    <a href="{{route('buyer.adsAction',['edit',$enquiry->id])}}" class="alert-link ms-1">Edit &amp; resubmit</a>
  </div>
  @endif

  <dl class="av-kv mt-3">
    <dt>Category</dt><dd>{{$enquiry->category?->name ?: 'Any category'}}</dd>
    <dt>Quantity</dt><dd>{{$enquiry->quantityLabel() ?: '—'}}</dd>
    <dt>Status</dt><dd>{{$enquiry->isPending() ? 'Pending approval' : ($enquiry->isRejected() ? 'Rejected' : ($enquiry->isClosed() ? 'Closed' : ($enquiry->isExpired() ? 'Expired' : 'Open')))}}</dd>
    <dt>Details</dt><dd>{!! $enquiry->description ? nl2br(e($enquiry->description)) : '—' !!}</dd>
  </dl>

  <div class="d-flex gap-2 flex-wrap mt-3">
    @unless($enquiry->isClosed())
    <form action="{{route('buyer.enquiriesAction',['close',$enquiry->id])}}" method="POST" data-confirm="Close this enquiry? Sellers will not be able to send quotes." data-confirm-ok="Yes, close">
      @csrf
      <button type="submit" class="btn-av-outline"><i class="fa-regular fa-circle-check me-1"></i> Close Enquiry</button>
    </form>
    @endunless
    <form action="{{route('buyer.enquiriesAction',['delete',$enquiry->id])}}" method="POST" data-confirm="Delete this enquiry and its quotes?" data-confirm-ok="Yes, delete" data-confirm-danger>
      @csrf
      <button type="submit" class="btn-av-outline"><i class="fa-regular fa-trash-can me-1"></i> Delete</button>
    </form>
  </div>
</div>

<div class="av-card">
  <div class="av-card-title"><i class="fa-solid fa-file-invoice-dollar"></i> Quotes ({{$quotes->count()}})</div>
  @forelse($quotes as $quote)
  <div class="order-row">
    <span class="enquiry-ic"><i class="fa-solid fa-store"></i></span>
    <div class="flex-grow-1">
      <div class="order-title">{{$quote->user?->name ?: 'Seller'}}</div>
      @if($quote->message)<div class="order-seller">{{Str::limit($quote->message,140)}}</div>@endif
    </div>
    <div class="order-price">@if($quote->price!==null){{$cur}} {{number_format($quote->price)}}@else—@endif</div>
    <div class="text-end"><div class="order-date">{{$quote->created_at?->format('M d, Y')}}</div></div>
    @if($quote->user_id)
    <a href="{{route('buyer.messages',['to'=>$quote->user_id])}}" class="btn-av-outline btn-sm py-1 px-2" title="Chat with the seller"><i class="fa-regular fa-comments"></i></a>
    @endif
  </div>
  @empty
  <div class="av-empty"><i class="fa-regular fa-comment-dots"></i>No quotes yet. Sellers' quotes for this enquiry will appear here.</div>
  @endforelse
</div>
@endsection
