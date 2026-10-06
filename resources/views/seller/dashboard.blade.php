@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Seller Dashboard')}}</title>
@endsection

@section('contents')
@php 
  $cur = $summary['currency']; 
  $activeAdsCount = $stats['active'];
  $totalAdsCount = $stats['ads'];
  $ordersCount = $stats['orders'];
  $quotesCount = $stats['quotes'];
  $totalEarned = $stats['earned'];
@endphp

<div class="container-fluid px-0">
  {{-- ==================== ROW 1: Gamification Card + 4 Frest Metric Cards ==================== --}}
  <div class="row g-4 mb-4">
    <!-- Gamification Banner Card (Frest eCommerce signature top widget) -->
    <div class="col-xl-4 col-lg-5 col-12">
      <div class="card frest-gamification-card h-100">
        <div class="card-body d-flex flex-column justify-content-between">
          <div>
            <h4 class="gamification-title">Congratulations {{ Str::before($user->name, ' ') }}! 🎉</h4>
            <p class="gamification-sub">Best seller of the store</p>
            <div class="row align-items-center">
              <div class="col-7">
                <div class="gamification-highlight">{{ $cur }} {{ number_format($totalEarned) }}</div>
                <p class="gamification-note">You have <b>{{ number_format($activeAdsCount) }}</b> active ads running in the marketplace today.</p>
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ route('seller.listingsAction', 'create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i> Post Ad
                  </a>
                  <a href="{{ route('seller.orders') }}" class="btn btn-outline-primary btn-sm">
                    View Orders
                  </a>
                </div>
              </div>
              <div class="col-5 text-end">
                <div class="gamification-art">
                  <svg class="gamification-svg" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="80" cy="80" r="72" fill="#F4F7FE" stroke="#E6EEFD" stroke-width="2"/>
                    <path d="M48 62C48 55.3726 53.3726 50 60 50H100C106.627 50 112 55.3726 112 62V106C112 112.627 106.627 118 100 118H60C53.3726 118 48 112.627 48 106V62Z" fill="url(#storeGrad)" stroke="#5A8DEE" stroke-width="2.5"/>
                    <path d="M44 64H116L110 50H50L44 64Z" fill="#5A8DEE"/>
                    <rect x="70" y="80" width="20" height="38" rx="2" fill="#FFFFFF"/>
                    <circle cx="80" cy="98" r="2" fill="#5A8DEE"/>
                    <path d="M54 74H68V92H54V74Z" fill="#FFFFFF" fill-opacity="0.9" rx="2"/>
                    <path d="M92 74H106V92H92V74Z" fill="#FFFFFF" fill-opacity="0.9" rx="2"/>
                    <!-- Star badge -->
                    <circle cx="112" cy="50" r="16" fill="#FDAC41"/>
                    <path d="M112 43L114.2 47.5L119.1 48.2L115.6 51.6L116.4 56.5L112 54.2L107.6 56.5L108.4 51.6L104.9 48.2L109.8 47.5L112 43Z" fill="#FFFFFF"/>
                    <defs>
                      <linearGradient id="storeGrad" x1="80" y1="50" x2="80" y2="118" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#FFFFFF"/>
                        <stop stop-color="#E8F0FE" offset="1"/>
                      </linearGradient>
                    </defs>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4 eCommerce Metric Cards -->
    <div class="col-xl-8 col-lg-7 col-12">
      <div class="row g-3 h-100">
        <!-- Metric 1: Active Ads -->
        <div class="col-sm-6 col-12">
          <div class="card frest-stat-card">
            <div class="card-body">
              <div class="avatar-initial rounded-circle bg-label-success">
                <i class="bx bx-check-circle"></i>
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div class="stat-label">Active Listings</div>
                <div class="stat-value">{{ number_format($activeAdsCount) }}</div>
                <div class="stat-sub text-success">
                  <i class="bx bx-trending-up"></i> Live on Site
                </div>
              </div>
              <a href="{{ route('seller.listings') }}" class="btn btn-sm btn-outline-primary">View</a>
            </div>
          </div>
        </div>

        <!-- Metric 2: Total Ads -->
        <div class="col-sm-6 col-12">
          <div class="card frest-stat-card">
            <div class="card-body">
              <div class="avatar-initial rounded-circle bg-label-primary">
                <i class="bx bx-cube"></i>
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div class="stat-label">Total Listings</div>
                <div class="stat-value">{{ number_format($totalAdsCount) }}</div>
                <div class="stat-sub text-primary">
                  <i class="bx bx-layer"></i> Store Inventory
                </div>
              </div>
              <a href="{{ route('seller.listings') }}" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
          </div>
        </div>

        <!-- Metric 3: Orders Received -->
        <div class="col-sm-6 col-12">
          <div class="card frest-stat-card">
            <div class="card-body">
              <div class="avatar-initial rounded-circle bg-label-info">
                <i class="bx bx-cart"></i>
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div class="stat-label">Orders Received</div>
                <div class="stat-value">{{ number_format($ordersCount) }}</div>
                <div class="stat-sub text-info">
                  <i class="bx bx-package"></i> Customer Orders
                </div>
              </div>
              <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-outline-primary">Orders</a>
            </div>
          </div>
        </div>

        <!-- Metric 4: Quotes Sent -->
        <div class="col-sm-6 col-12">
          <div class="card frest-stat-card">
            <div class="card-body">
              <div class="avatar-initial rounded-circle bg-label-warning">
                <i class="bx bx-send"></i>
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div class="stat-label">Quotes Sent</div>
                <div class="stat-value">{{ number_format($quotesCount) }}</div>
                <div class="stat-sub text-warning">
                  <i class="bx bx-briefcase-alt"></i> Buyer Requirements
                </div>
              </div>
              <a href="{{ route('seller.enquiries', ['status' => 'quoted']) }}" class="btn btn-sm btn-outline-primary">Quotes</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== ROW 2: Order Summary Split Card + Notifications ==================== --}}
  <div class="row g-4 mb-4">
    <!-- Frest Signature Split Card (Weekly Order Summary + Sales Overview) -->
    <div class="col-xl-8 col-12">
      <div class="card frest-order-summary-card h-100">
        <div class="row g-0 h-100">
          <!-- Split Left: Weekly Activity Bar Breakdown -->
          <div class="col-md-7 col-12 split-left">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="card-title mb-0"><i class="bx bx-bar-chart-alt-2"></i> Weekly Order Activity</h5>
              <span class="badge bg-label-primary">Current Week</span>
            </div>

            <div class="frest-bar-chart">
              <div class="bar-col">
                <div class="bar-pill bar-h-45"></div>
                <span class="bar-day">Mon</span>
              </div>
              <div class="bar-col">
                <div class="bar-pill bar-h-65"></div>
                <span class="bar-day">Tue</span>
              </div>
              <div class="bar-col">
                <div class="bar-pill bar-h-40"></div>
                <span class="bar-day">Wed</span>
              </div>
              <div class="bar-col">
                <div class="bar-pill bar-h-90 active"></div>
                <span class="bar-day">Thu</span>
              </div>
              <div class="bar-col">
                <div class="bar-pill bar-h-70"></div>
                <span class="bar-day">Fri</span>
              </div>
              <div class="bar-col">
                <div class="bar-pill bar-h-55"></div>
                <span class="bar-day">Sat</span>
              </div>
              <div class="bar-col">
                <div class="bar-pill bar-h-80"></div>
                <span class="bar-day">Sun</span>
              </div>
            </div>

            <div class="d-flex flex-wrap justify-content-between gap-2 pt-2">
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-label-success rounded-circle p-2"><i class="bx bx-check"></i></span>
                <div>
                  <small class="text-muted d-block">Active Ads</small>
                  <span class="fw-semibold">{{ number_format($activeAdsCount) }}</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-label-info rounded-circle p-2"><i class="bx bx-cart"></i></span>
                <div>
                  <small class="text-muted d-block">Total Orders</small>
                  <span class="fw-semibold">{{ number_format($ordersCount) }}</span>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-label-warning rounded-circle p-2"><i class="bx bx-send"></i></span>
                <div>
                  <small class="text-muted d-block">Quotes Sent</small>
                  <span class="fw-semibold">{{ number_format($quotesCount) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Split Right: Sales Overview Progress Stats -->
          <div class="col-md-5 col-12 split-right d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="card-title mb-0">Sales Overview</h5>
                <span class="text-success small fw-semibold"><i class="bx bx-up-arrow-alt"></i> Store Live</span>
              </div>
              <p class="text-muted small mb-4">Performance metrics for your marketplace inventory.</p>

              <!-- Progress 1: Total Revenue -->
              <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <div class="d-flex align-items-center gap-2">
                    <span class="avatar-initial rounded bg-label-primary p-1"><i class="bx bx-dollar"></i></span>
                    <span class="small fw-semibold">Total Revenue</span>
                  </div>
                  <span class="small fw-bold">{{ $cur }} {{ number_format($totalEarned) }}</span>
                </div>
                <div class="progress progress-h6">
                  <div class="progress-bar bg-primary w-pct-78" role="progressbar"></div>
                </div>
              </div>

              <!-- Progress 2: Inventory Conversion -->
              <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <div class="d-flex align-items-center gap-2">
                    <span class="avatar-initial rounded bg-label-success p-1"><i class="bx bx-check"></i></span>
                    <span class="small fw-semibold">Inventory Active</span>
                  </div>
                  <span class="small fw-bold">{{ $activeAdsCount }} / {{ $totalAdsCount }}</span>
                </div>
                <div class="progress progress-h6">
                  <div class="progress-bar bg-success w-pct-85" role="progressbar"></div>
                </div>
              </div>

              <!-- Progress 3: Quote Conversion -->
              <div>
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <div class="d-flex align-items-center gap-2">
                    <span class="avatar-initial rounded bg-label-info p-1"><i class="bx bx-message-check"></i></span>
                    <span class="small fw-semibold">Response Health</span>
                  </div>
                  <span class="small fw-bold">92%</span>
                </div>
                <div class="progress progress-h6">
                  <div class="progress-bar bg-info w-pct-92" role="progressbar"></div>
                </div>
              </div>
            </div>

            <div class="pt-3">
              <a href="{{ route('seller.orders') }}" class="btn btn-primary w-100 btn-sm">
                <i class="bx bx-receipt me-1"></i> View Order History
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notifications & Store Activity Feed -->
    <div class="col-xl-4 col-12">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-bell"></i> Notifications</h5>
          <a href="{{ route('seller.notifications') }}" class="text-primary small fw-semibold">View all</a>
        </div>
        <div class="card-body py-2">
          @forelse($activity as $item)
            <div class="frest-activity-item">
              <div class="activity-avatar">
                <i class="bx {{ str_contains($item['icon'], 'check') ? 'bx-check-circle' : (str_contains($item['icon'], 'xmark') ? 'bx-x-circle' : 'bx-bell') }}"></i>
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between gap-1">
                  <div class="activity-title text-truncate">{{ $item['title'] }}</div>
                  @if(!empty($item['unread']))
                    <span class="badge bg-label-danger">New</span>
                  @endif
                </div>
                <div class="activity-sub text-truncate">{{ $item['sub'] }}</div>
                <div class="text-muted small mt-1"><i class="bx bx-time-five me-1"></i>{{ $item['date']->diffForHumans() }}</div>
              </div>
            </div>
          @empty
            <div class="av-empty py-4">
              <i class="bx bx-bell-off"></i>
              No notifications yet.
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  {{-- ==================== ROW 3: Recent Orders Table + Quick Actions & Requirements ==================== --}}
  <div class="row g-4">
    <!-- Recent Orders Received (Frest DataTables Card style) -->
    <div class="col-xl-8 col-12">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-receipt"></i> Recent Orders Received</h5>
          <a href="{{ route('seller.orders') }}" class="btn btn-outline-primary btn-sm">
            View All Orders <i class="bx bx-right-arrow-alt ms-1"></i>
          </a>
        </div>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Product</th>
                <th>Buyer</th>
                <th>Price</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentOrders as $order)
                @php
                  $ad = $orderAds->get($order->src_id);
                  $statusGroup = strtolower($order->statusGroup() ?: 'unknown');
                  $badgeClass = match($statusGroup) {
                    'completed', 'active' => 'bg-label-success',
                    'pending', 'waiting' => 'bg-label-warning',
                    'cancelled', 'rejected' => 'bg-label-danger',
                    default => 'bg-label-info',
                  };
                @endphp
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <img class="order-thumb" src="{{ asset($ad ? $ad->fiImage() : 'public/medies/noimage.jpg') }}" alt="{{ $ad?->name }}" onerror="this.onerror=null;this.src='{{ asset('public/medies/noimage.jpg') }}'">
                      <div class="overflow-hidden">
                        <div class="table-product-title text-truncate">
                          @if($ad && $ad->status == 'active')
                            <a href="{{ route('seller.marketplaceView', $ad->id) }}">{{ $ad->name }}</a>
                          @else
                            {{ $ad?->name ?: 'Order #' . $order->id }}
                          @endif
                        </div>
                        <div class="table-product-sub">Order #{{ $order->id }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="fw-medium text-dark">{{ $order->billing_name ?: ($buyers->get($order->user_id)?->name ?: '—') }}</span>
                  </td>
                  <td>
                    <span class="fw-bold text-dark">{{ $cur }} {{ number_format($order->amount) }}</span>
                  </td>
                  <td>
                    <span class="badge {{ $badgeClass }}">{{ $order->statusLabel() }}</span>
                  </td>
                  <td>
                    <span class="text-muted small">{{ $order->created_at?->format('M d, Y') }}</span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5">
                    <div class="av-empty py-4">
                      <i class="bx bx-receipt"></i>
                      No orders yet. Orders placed on your ads show up here.
                      <div class="mt-2">
                        <a href="{{ route('seller.listingsAction', 'create') }}" class="btn btn-primary btn-sm">
                          <i class="bx bx-plus me-1"></i> Post An Ad
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Quick Actions & Latest Buyer Requirements -->
    <div class="col-xl-4 col-12">
      <!-- Quick Actions -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-bolt-circle"></i> Quick Actions</h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <a href="{{ route('seller.listingsAction', 'create') }}" class="frest-action-tile">
              <i class="bx bx-plus-circle"></i>
              <span>Post New Ad</span>
            </a>
            <a href="{{ route('seller.enquiries') }}" class="frest-action-tile">
              <i class="bx bx-briefcase-alt-2"></i>
              <span>Browse Buyer Requirements</span>
            </a>
            <a href="{{ route('seller.listings') }}" class="frest-action-tile">
              <i class="bx bx-list-ul"></i>
              <span>Manage My Ads</span>
            </a>
            <a href="{{ route('seller.messages') }}" class="frest-action-tile">
              <i class="bx bx-chat"></i>
              <span>Open Messages</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Latest Buyer Requirements -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-message-square-detail"></i> Buyer Requirements</h5>
          <a href="{{ route('seller.enquiries') }}" class="text-primary small fw-semibold">View All</a>
        </div>
        <div class="card-body py-2">
          @forelse($requirements as $enquiry)
            @php
              $myQuote = $enquiry->quotes->first();
            @endphp
            <div class="frest-enquiry-item">
              <div class="enquiry-icon">
                <i class="bx bx-notepad"></i>
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <a href="{{ route('seller.enquiriesAction', ['view', $enquiry->id]) }}" class="enquiry-title text-truncate d-block">
                  {{ $enquiry->title }}
                </a>
                <div class="enquiry-meta text-truncate">
                  Buyer: {{ $enquiry->user?->name ?: '—' }} @if($enquiry->category) &middot; {{ $enquiry->category->name }} @endif
                </div>
                <div class="mt-1 d-flex align-items-center justify-content-between">
                  @if($myQuote)
                    <span class="badge bg-label-success">Quoted: {{ $cur }} {{ number_format($myQuote->price) }}</span>
                  @else
                    <span class="badge bg-label-warning">Open &middot; {{ $enquiry->quotes_count }} {{ $enquiry->quotes_count == 1 ? 'quote' : 'quotes' }}</span>
                  @endif
                  <small class="text-muted">{{ $enquiry->created_at?->format('M d') }}</small>
                </div>
              </div>
            </div>
          @empty
            <div class="av-empty py-4">
              <i class="bx bx-comment-x"></i>
              No open requirements right now.
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

