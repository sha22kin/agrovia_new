@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Profile Overview')}}</title>
@endsection

@section('contents')
@php $cur = $summary['currency']; @endphp

@include('dashboard.partials.banner')

@include('dashboard.partials.stats',['items'=>[
  ['Total Ads',number_format($stats['total']),'fa-rectangle-list','var(--av-green-100)','var(--av-green-700)','View Ads',route('seller.listings')],
  ['Active Ads',number_format($stats['active']),'fa-circle-check','#e7f6ec','var(--av-green-600)','Live on the site',null],
  ['Pending Approval',number_format($stats['pending']),'fa-hourglass-half','#fff4dc','#a86a00','Waiting for review',null],
  ['Sold',number_format($stats['solded']),'fa-handshake','#eaf1fb','#2563a8','Marked as sold',null],
  ['Total Views',number_format($stats['views']),'fa-eye','#fdeceb','#e0574c','Across all ads',null],
]])

<!-- Tabs -->
<div class="av-tabs" role="tablist">
  <button class="av-tab active" data-bs-toggle="tab" data-bs-target="#pane-overview" type="button" role="tab">Overview</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-business" type="button" role="tab">Business Information</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-prefs" type="button" role="tab">Selling Preferences</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-activity" type="button" role="tab">Activity</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-reviews" type="button" role="tab">Reviews</button>
</div>

<div class="tab-content">

  <!-- Overview -->
  <div class="tab-pane fade show active" id="pane-overview" role="tabpanel">
    <div class="row">
      <div class="col-lg-8">

        @include('dashboard.partials.about')

        <!-- Recent Ads -->
        <div class="av-card">
          <div class="d-flex justify-content-between align-items-center">
            <div class="av-card-title"><i class="fa-regular fa-rectangle-list"></i> Recent Ads</div>
            <a href="{{route('seller.listings')}}" class="av-link-more">View All Ads <i class="fa-solid fa-arrow-right"></i></a>
          </div>
          @forelse($recent as $ad)
          <div class="order-row">
            <img class="order-thumb" src="{{asset($ad->fiImage())}}" alt="" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'">
            <div class="flex-grow-1">
              <div class="order-title">
                @if($ad->status=='active' && $ad->slug)<a href="{{route('serviceView',$ad->slug)}}">{{Str::limit($ad->name,50)}}</a>@else{{Str::limit($ad->name,50)}}@endif
              </div>
              <div class="order-seller">{{$ad->category?->name ?: 'No category'}}</div>
            </div>
            <div class="order-price">{{$cur}} {{number_format($ad->price)}}</div>
            <div class="text-end">
              <span class="order-status status-{{strtolower($ad->status)}}">{{ucfirst($ad->status)}}</span>
              <div class="order-date">{{$ad->created_at?->format('M d, Y')}}</div>
            </div>
            <a href="{{route('seller.listingsAction',['edit',$ad->id])}}" class="btn btn-sm btn-outline-success ms-1" title="Edit"><i class="fa-solid fa-pen"></i></a>
          </div>
          @empty
          <div class="av-empty"><i class="fa-regular fa-rectangle-list"></i>You have not posted any ad yet. <a href="{{route('seller.listingsAction','create')}}">Post your first ad</a></div>
          @endforelse
        </div>

        <!-- Recent Payments -->
        <div class="av-card">
          <div class="d-flex justify-content-between align-items-center">
            <div class="av-card-title"><i class="fa-solid fa-credit-card"></i> Recent Payments</div>
            <a href="{{route('seller.payments')}}" class="av-link-more">View All Payments <i class="fa-solid fa-arrow-right"></i></a>
          </div>
          @forelse($payments as $payment)
          <div class="enquiry-row mt-3">
            <span class="enquiry-ic"><i class="fa-solid fa-receipt"></i></span>
            <div class="flex-grow-1">
              <div class="fw-semibold" style="font-size:.87rem;">{{$payment->billing_name ?: 'Payment #'.$payment->id}}</div>
              <div class="text-muted" style="font-size:.78rem;">{{$payment->payment_method ?: '—'}}</div>
            </div>
            <div class="text-end">
              <div class="fw-semibold" style="font-size:.85rem;">{{$cur}} {{number_format($payment->amount)}}</div>
              <span class="order-status status-{{strtolower($payment->status)}}">{{$payment->status}}</span>
            </div>
          </div>
          @empty
          <div class="av-empty"><i class="fa-solid fa-credit-card"></i>No payments yet.</div>
          @endforelse
        </div>

      </div>

      <!-- Right column -->
      <div class="col-lg-4">

        @include('dashboard.partials.verification')

        <div class="av-mini-card">
          <div class="av-card-title"><i class="fa-solid fa-chart-simple"></i> Ad Summary</div>
          @foreach([
            ['Active','fa-circle-check',$stats['active']],
            ['Pending approval','fa-hourglass-half',$stats['pending']],
            ['Sold','fa-handshake',$stats['solded']],
            ['Cancelled','fa-ban',$stats['cancelled']],
            ['Featured','fa-star',$stats['featured']],
          ] as [$label,$icon,$count])
          <div class="pref-item">
            <span class="pref-ic"><i class="fa-solid {{$icon}}"></i></span>
            <div>
              <div class="pref-label">{{$label}}</div>
              <div class="pref-value">{{number_format($count)}} {{Str::plural('ad',$count)}}</div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Promo card visible on mobile below content -->
        <div class="av-promo d-lg-none">
          <i class="fa-solid fa-tractor fig"></i>
          <h6>Sell More<br>With Agroviea</h6>
          <p>Post an ad with clear photos and reach buyers across Bangladesh.</p>
          <a href="{{route('seller.listingsAction','create')}}" class="btn btn-post"><i class="fa-solid fa-bullhorn me-1"></i> Post New Ad</a>
        </div>

      </div>
    </div>
  </div>

  <div class="tab-pane fade" id="pane-business" role="tabpanel">@include('dashboard.partials.business-pane')</div>

  <div class="tab-pane fade" id="pane-prefs" role="tabpanel">
    <div class="av-card first">
      <div class="av-card-title mb-2"><i class="fa-solid fa-sliders"></i> Categories You Sell In</div>
      @forelse($categories as $categoryId=>$total)
      <div class="pref-item">
        <span class="pref-ic"><i class="fa-solid fa-wheat-awn"></i></span>
        <div>
          <div class="pref-label">{{$categoryNames->get($categoryId,'Other category')}}</div>
          <div class="pref-value">{{$total}} {{Str::plural('ad',$total)}}</div>
        </div>
      </div>
      @empty
      <div class="av-empty"><i class="fa-solid fa-sliders"></i>Post an ad and the categories you sell in will show up here.</div>
      @endforelse
    </div>
  </div>

  <div class="tab-pane fade" id="pane-activity" role="tabpanel">@include('dashboard.partials.activity-pane')</div>
  <div class="tab-pane fade" id="pane-reviews" role="tabpanel">@include('dashboard.partials.reviews-pane')</div>

</div>
@endsection
