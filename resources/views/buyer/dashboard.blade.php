@extends(buyerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Buyer Dashboard')}}</title>
@endsection

@section('contents')
@php 
  $cur = $summary['currency']; 
  $ordersCount = $stats['orders'];
  $enquiriesCount = $stats['enquiries'];
  $wishlistCount = $stats['wishlist'];
  $adsCount = $stats['ads'];
  $totalSpent = $stats['spent'];
@endphp

<div class="container-fluid px-0">
  {{-- ==================== ROW 1: Frest Welcome Banner + 5 eCommerce Stat Cards ==================== --}}
  <div class="card frest-gamification-card mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="gamification-title mb-1">Welcome back, {{ Str::before($user->name, ' ') }}! 👋</h4>
          <p class="gamification-sub mb-0">Here's what's happening with your buying activities and orders today.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('buyer.enquiriesAction', 'create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Post Requirement
          </a>
          <a href="{{ route('buyer.marketplace') }}" class="btn btn-outline-primary btn-sm">
            <i class="bx bx-store-alt me-1"></i> Browse Marketplace
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- 5 Frest Metric Cards --}}
  <div class="row g-3 mb-4">
    <!-- Metric 1: Total Orders -->
    <div class="col-xl col-md-4 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-primary">
            <i class="bx bx-receipt"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($ordersCount) }}</div>
            <div class="stat-sub text-primary">
              <i class="bx bx-package"></i> Purchases
            </div>
          </div>
          <a href="{{ route('buyer.orders') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 2: Enquiries Sent -->
    <div class="col-xl col-md-4 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-info">
            <i class="bx bx-briefcase-alt-2"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Enquiries Sent</div>
            <div class="stat-value">{{ number_format($enquiriesCount) }}</div>
            <div class="stat-sub text-info">
              <i class="bx bx-send"></i> Requirements
            </div>
          </div>
          <a href="{{ route('buyer.enquiries') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 3: Saved Items -->
    <div class="col-xl col-md-4 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-danger">
            <i class="bx bx-heart"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Saved Items</div>
            <div class="stat-value">{{ number_format($wishlistCount) }}</div>
            <div class="stat-sub text-danger">
              <i class="bx bxs-heart"></i> Wishlist
            </div>
          </div>
          <a href="{{ route('buyer.wishlist') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 4: Active Ads -->
    <div class="col-xl col-md-6 col-sm-6 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-warning">
            <i class="bx bx-list-ul"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Active Ads</div>
            <div class="stat-value">{{ number_format($adsCount) }}</div>
            <div class="stat-sub text-warning">
              <i class="bx bx-layer"></i> Listings
            </div>
          </div>
          <a href="{{ route('buyer.ads') }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>

    <!-- Metric 5: Total Spent -->
    <div class="col-xl col-md-6 col-sm-12 col-12">
      <div class="card frest-stat-card h-100">
        <div class="card-body">
          <div class="avatar-initial rounded-circle bg-label-success">
            <i class="bx bx-wallet"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="stat-label">Total Spent</div>
            <div class="stat-value">{{ $cur }} {{ number_format($totalSpent) }}</div>
            <div class="stat-sub text-success">
              <i class="bx bx-check-double"></i> Completed
            </div>
          </div>
          <a href="{{ route('buyer.orders', ['status' => 'Completed']) }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== ROW 2: Orders, Enquiries & Sidebar Cards ==================== --}}
  <div class="row g-4">
    {{-- Left Main Column: Orders & Enquiries --}}
    <div class="col-lg-8">
      @include('dashboard.partials.recent-orders', ['role' => 'buyer'])
      @include(buyerTheme() . 'partials.recent-enquiries')
    </div>

    {{-- Right Sidebar Column: Notifications & Quick Actions --}}
    <div class="col-lg-4">
      <!-- Notifications Card -->
      <div class="card mb-4">
        <div class="card-header pb-2">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="bx bx-bell text-primary"></i> Notifications
          </h5>
        </div>
        <div class="card-body pt-2">
          @forelse($activity as $item)
            <div class="verify-item">
              <i class="fa-solid {{ $item['icon'] }}"></i>
              <div class="overflow-hidden">
                <div class="fw-semibold text-truncate">{{ $item['title'] }}</div>
                <div class="verify-item-sub">{{ $item['sub'] }} · {{ $item['date']->diffForHumans() }}</div>
              </div>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="bx bx-bell-off fs-2 mb-2 d-block"></i>
              No notifications yet.
            </div>
          @endforelse
        </div>
      </div>

      <!-- Quick Actions Card -->
      <div class="card mb-4">
        <div class="card-header pb-2">
          <h5 class="card-title mb-0 d-flex align-items-center gap-2">
            <i class="bx bx-bolt-circle text-primary"></i> Quick Actions
          </h5>
        </div>
        <div class="card-body pt-2">
          <div class="d-grid gap-2">
            <a href="{{ route('buyer.marketplace') }}" class="btn btn-outline-primary text-start d-flex align-items-center gap-2">
              <i class="bx bx-store-alt fs-5"></i> Browse Marketplace
            </a>
            <a href="{{ route('buyer.enquiriesAction', 'create') }}" class="btn btn-outline-primary text-start d-flex align-items-center gap-2">
              <i class="bx bx-comment-detail fs-5"></i> Post an Enquiry
            </a>
            <a href="{{ route('buyer.orders') }}" class="btn btn-outline-primary text-start d-flex align-items-center gap-2">
              <i class="bx bx-receipt fs-5"></i> View My Orders
            </a>
            <form action="{{ route('buyer.becomeSeller') }}" method="POST" data-confirm="Activate your seller account so you can post ads?" data-confirm-ok="Yes, activate">
              @csrf
              <button type="submit" class="btn btn-outline-primary text-start w-100 d-flex align-items-center gap-2">
                <i class="bx bx-plus-circle fs-5"></i> Start Selling
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Mobile Promo Card -->
      <div class="av-promo d-lg-none">
        <h6>Grow Your Business with {{ general()->title ?: 'Agroviea' }}</h6>
        <p>Post your buying requirement and get best quotes from verified sellers.</p>
        <a href="{{ route('buyer.enquiriesAction', 'create') }}" class="btn-post text-decoration-none">
          <i class="bx bx-plus me-1"></i> Post Requirement
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
