@extends(buyerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Profile Overview')}}</title>
@endsection

@section('contents')
@php $cur = $summary['currency']; @endphp

@include('dashboard.partials.banner')

@include('dashboard.partials.stats',['items'=>[
  ['Total Orders',number_format($stats['orders']),'fa-bag-shopping','var(--av-green-100)','var(--av-green-700)','View Orders',route('buyer.orders')],
  ['Completed Orders',number_format($stats['completed']),'fa-check','#e7f6ec','var(--av-green-600)','View Completed',route('buyer.orders',['status'=>'Completed'])],
  ['Total Spent',$cur.' '.number_format($stats['spent']),'fa-sack-dollar','#e7f6ec','var(--av-green-600)','View Transactions',route('buyer.orders',['status'=>'Completed'])],
  ['Enquiries Sent',number_format($stats['enquiries']),'fa-truck-fast','#eaf1fb','#2563a8','View Enquiries',route('buyer.enquiries')],
  ['Saved Items',number_format($stats['wishlist']),'fa-heart','#fdeceb','#e0574c','View Wishlist',route('buyer.wishlist')],
]])

<!-- Tabs -->
<div class="av-tabs" role="tablist">
  <button class="av-tab active" data-bs-toggle="tab" data-bs-target="#pane-overview" type="button" role="tab">Overview</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-business" type="button" role="tab">Business Information</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-prefs" type="button" role="tab">Buying Preferences</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-activity" type="button" role="tab">Activity</button>
  <button class="av-tab" data-bs-toggle="tab" data-bs-target="#pane-reviews" type="button" role="tab">Reviews</button>
</div>

<div class="tab-content">

  <!-- Overview -->
  <div class="tab-pane fade show active" id="pane-overview" role="tabpanel">
    <div class="row">
      <div class="col-lg-8">

        @include('dashboard.partials.about')

        @include('dashboard.partials.recent-orders',['role'=>'buyer'])

    @include(buyerTheme().'partials.recent-enquiries')

      </div>

      <!-- Right column -->
      <div class="col-lg-4">

        @include('dashboard.partials.verification')

        <div class="av-mini-card">
          <div class="d-flex justify-content-between align-items-center">
            <div class="av-card-title"><i class="fa-solid fa-sliders"></i> Buying Preferences</div>
            <a href="{{route('buyer.preferences')}}" class="av-link-more">Edit</a>
          </div>
          @include(buyerTheme().'partials.preferences')
        </div>

        <div class="av-mini-card">
          <div class="av-card-title"><i class="fa-solid fa-store"></i> Sell on Agroviea</div>
          <p class="text-muted mt-2 mb-2" style="font-size:.82rem;">Activate a seller account to post ads. Your buyer account stays the same.</p>
          <form action="{{route('buyer.becomeSeller')}}" method="POST" data-confirm="Activate your seller account so you can post ads?" data-confirm-ok="Yes, activate">
            @csrf
            <button type="submit" class="btn btn-theme btn-sm w-100"><i class="fa-solid fa-bullhorn me-1"></i> Become a Seller</button>
          </form>
        </div>

        <!-- Promo card visible on mobile below content -->
        <div class="av-promo d-lg-none">
          <i class="fa-solid fa-tractor fig"></i>
          <h6>Grow Your Business<br>With Agroviea</h6>
          <p>Post your buying requirement and get best sellers.</p>
          <a href="{{route('buyer.enquiriesAction','create')}}" class="btn btn-post"><i class="fa-solid fa-bullhorn me-1"></i> Post Requirement</a>
        </div>

      </div>
    </div>
  </div>

  <div class="tab-pane fade" id="pane-business" role="tabpanel">@include('dashboard.partials.business-pane')</div>

  <div class="tab-pane fade" id="pane-prefs" role="tabpanel">
    <div class="av-card first">
      <div class="d-flex justify-content-between align-items-center">
        <div class="av-card-title"><i class="fa-solid fa-sliders"></i> Buying Preferences</div>
        <a href="{{route('buyer.preferences')}}" class="av-link-more">Edit <i class="fa-solid fa-arrow-right"></i></a>
      </div>
      @include(buyerTheme().'partials.preferences')
    </div>
  </div>

  <div class="tab-pane fade" id="pane-activity" role="tabpanel">@include('dashboard.partials.activity-pane')</div>
  <div class="tab-pane fade" id="pane-reviews" role="tabpanel">@include('dashboard.partials.reviews-pane')</div>

</div>
@endsection
