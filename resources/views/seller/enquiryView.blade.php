@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle($enquiry->title)}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">Buyer Requirement</h5>
  <a href="{{route('seller.enquiries')}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to Requirements</a>
</div>

<div class="row g-3">
  <div class="col-lg-7">
    <div class="av-card mt-0">
      <div class="fw-bold" style="font-size:1.05rem;">{{$enquiry->title}}</div>
      <div class="text-muted" style="font-size:.8rem;">Posted {{$enquiry->created_at?->format('M d, Y')}}@if($enquiry->expires_at) &middot; Open until {{$enquiry->expires_at->format('M d, Y')}}@endif</div>
      <dl class="av-kv mt-3">
        <dt>Buyer</dt><dd>{{$enquiry->user?->name ?: '—'}}@if($enquiry->user?->company_name) <span class="text-muted">({{$enquiry->user->company_name}})</span>@endif</dd>
        <dt>Category</dt><dd>{{$enquiry->category?->name ?: 'Any category'}}</dd>
        <dt>Quantity</dt><dd>{{$enquiry->quantityLabel() ?: '—'}}</dd>
        <dt>Quotes so far</dt><dd>{{$enquiry->quotes_count}}</dd>
        <dt>Details</dt><dd>{!! $enquiry->description ? nl2br(e($enquiry->description)) : '—' !!}</dd>
      </dl>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="av-mini-card mt-0">
      <div class="av-card-title"><i class="fa-solid fa-file-invoice-dollar"></i> {{$quote ? 'Your Quote' : 'Send a Quote'}}</div>
      <form method="POST" action="{{route('seller.enquiriesAction',['quote',$enquiry->id])}}" class="mt-2">
        @csrf
        <label class="av-form-label">Your price ({{$currency}}) <span class="text-danger">*</span></label>
        <input type="number" step="any" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{old('price',$quote ? (float) $quote->price : '')}}" placeholder="e.g. 75000" required>
        @error('price')<div class="invalid-feedback">{{$message}}</div>@enderror

        <label class="av-form-label mt-3">Message to the buyer</label>
        <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="4" maxlength="1000" placeholder="Quality, delivery time, terms...">{{old('message',$quote?->message)}}</textarea>
        @error('message')<div class="invalid-feedback">{{$message}}</div>@enderror

        <button type="submit" class="btn-av-primary w-100 mt-3"><i class="fa-solid fa-paper-plane me-2"></i>{{$quote ? 'Update Quote' : 'Send Quote'}}</button>
      </form>
      @if($quote)
      <form method="POST" action="{{route('seller.enquiriesAction',['withdraw',$enquiry->id])}}" class="mt-2" data-confirm="Withdraw your quote? The buyer will not see it anymore." data-confirm-ok="Yes, withdraw" data-confirm-danger>
        @csrf
        <button type="submit" class="btn-av-danger w-100"><i class="fa-regular fa-trash-can me-1"></i> Withdraw Quote</button>
      </form>
      <div class="text-muted mt-2" style="font-size:.74rem;">Sent {{$quote->created_at?->format('M d, Y')}}. The buyer can chat with you from the quote.</div>
      @endif
    </div>
  </div>
</div>
@endsection
