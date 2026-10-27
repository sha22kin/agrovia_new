@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{$page->seo_title?:websiteTitle($page->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$page->seo_title?:websiteTitle($page->name)}}" />
<meta name="description" property="og:description" content="{!!$page->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($page->image())}}" />
<meta name="url" property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('pageView',$page->slug?:'no-title')}}" />
@endsection @push('css')
<style>
.row {
    gap: 0;
}
/* The cards share the home page RFQ card styles (newStyle.css); here the Bootstrap column sets the width. */
.rfq-list .rfq-card { flex:none; max-width:none; height:100%; }
.rfq-list .rfq-body h4 { overflow-wrap:anywhere; }
.rfq-list .rfq-desc { font-size:13px; color:var(--muted); margin:8px 0 0; overflow-wrap:anywhere; }
</style>
@endpush @section('contents')



<div class="container agv-page-head">
  <div class="agv-eyebrow">REQUEST FOR QUOTATION</div>
  <h1>Tell sellers what you need. Let the quotes come to you.</h1>
  <p>Post one requirement — relevant farmers, suppliers and agro companies get notified and respond with price, quantity and delivery time.</p>
</div>

<div class="container agv-section">
  <div class="row g-4">

    <!-- Open requirements: enquiries the admin approved -->
    <div class="col-lg-8">
      <h5 class="section-title" style="font-size:16px; text-transform:uppercase; letter-spacing:.04em; color:#6d7d70;">Open requirements</h5>
      <p style="margin-top:-14px; color:var(--muted); font-size:13.5px;">Live buyer requests waiting for seller quotes.</p>

      @php
        $viewer = auth()->user();
        $canQuote = $viewer && $viewer->isSeller();
      @endphp

      @if($rfqs->isEmpty())
      <div class="agv-panel text-center" style="padding:40px 18px;">
        <i class="fa fa-bullhorn" style="font-size:26px; color:#9ab8a2;"></i>
        <h6 class="mt-2 mb-1">No open requirements right now</h6>
        <p style="font-size:13.5px; color:var(--muted); margin:0;">New buyer requests appear here after our team reviews them.</p>
      </div>
      @else
      <div class="row g-3 mt-1 rfq-list">
        @foreach($rfqs as $rfq)
        <div class="col-md-6">
          <div class="rfq-card">
            <div class="rfq-thumb">
              <img src="{{ asset($rfq->coverImage()) }}" alt="{{ $rfq->category?->name ?: $rfq->title }}" loading="lazy">
              <span class="rfq-status"><i></i> Open</span>
              <span class="rfq-quotes">{{ $rfq->quotes_count }} {{ \Illuminate\Support\Str::plural('quote', $rfq->quotes_count) }}</span>
            </div>

            <div class="rfq-body">
              <h4 title="{{ $rfq->title }}">{{ $rfq->title }}</h4>
              <div class="rfq-buyer">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                {{ $rfq->user?->name ?: 'Buyer' }} · {{ $rfq->category?->name ?: 'Any category' }}
              </div>
              @if($rfq->description)
              <p class="rfq-desc">{{ \Illuminate\Support\Str::limit($rfq->description, 110) }}</p>
              @endif

              <div class="rfq-facts">
                <div><span>Quantity</span><b class="qty">{{ $rfq->quantityLabel() ?: '—' }}</b></div>
                <div><span>Posted</span><b>{{ $rfq->created_at?->diffForHumans() }}</b></div>
                <div><span>Open until</span><b>{{ $rfq->expires_at ? $rfq->expires_at->format('d M Y') : 'Until closed' }}</b></div>
              </div>

              @if($canQuote && $rfq->user_id != $viewer->id)
              <a href="{{ route('seller.enquiriesAction', ['view', $rfq->id]) }}" class="btn-submit-quote">Submit a quote</a>
              @elseif($viewer && $rfq->user_id == $viewer->id)
              <span class="btn-submit-quote" style="opacity:.6; pointer-events:none;">Your requirement</span>
              @elseif($viewer)
              <a href="{{ route('seller.enquiriesAction', ['view', $rfq->id]) }}" class="btn-submit-quote" title="A seller account is needed to send quotes">Submit a quote</a>
              @else
              <a href="{{ route('login') }}" class="btn-submit-quote">Log in to quote</a>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>

      @if($rfqs->hasPages())
      <div class="agv-pagination mt-4">{{ $rfqs->links('pagination::bootstrap-5') }}</div>
      @endif
      @endif
    </div>

    <!-- Post requirement: opens the buyer's enquiry form with these values filled in -->
    <div class="col-lg-4">
      <div class="agv-panel">
        <h6 style="font-size:15px; margin-bottom:4px;">Post your requirement</h6>
        <p style="font-size:12.5px; color:var(--muted); margin-bottom:16px;">Free for buyers. Our team reviews each request before it goes live for sellers.</p>

        <form action="{{ route('buyer.enquiriesAction', 'create') }}" method="GET">
          <div class="mb-3">
            <div class="agv-form-label">What are you looking for?</div>
            <input type="text" name="title" class="agv-form-control" placeholder="e.g. 10 ton quality potato" maxlength="191" required>
          </div>

          <div class="mb-3">
            <div class="agv-form-label">Category</div>
            <select name="category" class="agv-form-control">
              <option value="">Any category</option>
              @foreach($rfqCategories as $ctg)
              <option value="{{ $ctg->id }}">{{ $ctg->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-7">
              <div class="agv-form-label">Quantity</div>
              <input type="number" name="quantity" step="any" min="0" class="agv-form-control" placeholder="10000">
            </div>
            <div class="col-5">
              <div class="agv-form-label">Unit</div>
              <select name="unit" class="agv-form-control">
                @foreach(\App\Models\Enquiry::UNITS as $unit)
                <option value="{{ $unit }}">{{ $unit }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mb-3">
            <div class="agv-form-label">Quality, delivery &amp; budget</div>
            <textarea name="description" class="agv-form-control" rows="3" maxlength="3000" placeholder="Grade A, delivery to Dhaka within 7 days, ৳20-24/kg"></textarea>
          </div>

          <button type="submit" class="btn-agv-solid block">Continue</button>
          <p style="font-size:11.5px; color:var(--muted); margin:8px 0 0; text-align:center;">You will review and post it from your buyer account.</p>
        </form>
      </div>
    </div>

  </div>
</div>


@endsection @push('js') @endpush