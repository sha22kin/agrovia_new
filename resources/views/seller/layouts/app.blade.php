{{-- Seller theme layout: Frest eCommerce Admin Template Style --}}
@php
    $dashRole = 'seller';
    $authUser = Auth::user();
    $unreadMessages = \App\Models\Message::unreadCount($authUser);
    $unreadNotifications = $authUser->unreadNotifications()->count();
    $topCategories = \App\Models\Attribute::where('type', 0)
        ->where('status', 'active')
        ->whereNull('parent_id')
        ->orderBy('name')
        ->get(['id', 'name']);

    $editingAd = request()->routeIs('seller.listingsAction') && isset($post);
    $pageCrumb = match (true) {
        request()->routeIs('seller.dashboard') => ['dashboard', 'Dashboard', 'bx-home-circle'],
        request()->routeIs('seller.marketplace', 'seller.marketplaceView') => ['storefront', 'Marketplace', 'bx-store-alt'],
        $editingAd && $post->status == 'temp' => ['add_circle', 'Post New Ad', 'bx-plus-circle'],
        $editingAd => ['edit_note', 'Edit Ad', 'bx-edit'],
        request()->routeIs('seller.listings', 'seller.listingsAction') => ['view_list', 'My Ads', 'bx-list-ul'],
        request()->routeIs('seller.orders') => ['receipt_long', 'Orders', 'bx-receipt'],
        request()->routeIs('seller.enquiries', 'seller.enquiriesAction') => ['assignment', 'Buyer Requirements', 'bx-briefcase-alt-2'],
        request()->routeIs('seller.wishlist') => ['favorite', 'My Wishlist', 'bx-heart'],
        request()->routeIs('seller.payments') => ['payments', 'Payments', 'bx-credit-card'],
        request()->routeIs('seller.messages') => ['chat', 'Messages', 'bx-chat'],
        request()->routeIs('seller.notifications') => ['notifications', 'Notifications', 'bx-bell'],
        request()->routeIs('seller.community', 'seller.communityAction') => ['groups', 'Community', 'bx-group'],
        request()->routeIs('seller.savedSearches') => ['manage_search', 'Saved Searches', 'bx-search-alt'],
        request()->routeIs('seller.profileOverview') => ['account_circle', 'Profile Overview', 'bx-user-pin'],
        request()->routeIs('seller.businessInfo', 'seller.businessDocumentAction') => ['business_center', 'Business Info', 'bx-buildings'],
        request()->routeIs('seller.addresses') => ['location_on', 'Addresses', 'bx-map-pin'],
        request()->routeIs('seller.paymentMethods') => ['account_balance_wallet', 'Payment Methods', 'bx-wallet'],
        request()->routeIs('seller.settings', 'seller.changePassword') => ['settings', 'Settings', 'bx-cog'],
        request()->routeIs('seller.profile') => ['manage_accounts', 'Edit Profile', 'bx-user'],
        default => ['dashboard', 'Seller Panel', 'bx-home-circle'],
    };
@endphp
<!DOCTYPE html>
<html lang="en" class="light-style layout-compact">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('title')
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(general()->favicon()) }}">

    <!-- Google Fonts: IBM Plex Sans & Rubik (Frest Typography) + Hind Siliguri -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Rubik:wght@400;500;600;700&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons: Boxicons (Frest signature icons) + FontAwesome + Material Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Stylesheets: Legacy compatibility + Clean + Frest Theme -->
    <link rel="stylesheet" href="{{ asset(assetLinkSeller() . '/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ assetV(assetLinkSeller() . '/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset(assetLinkSeller() . '/css/clean.css') }}">
    <link rel="stylesheet" href="{{ assetV(assetLinkSeller() . '/css/frest-theme.css') }}">
    @stack('css')
</head>

<body class="av-dashboard">

    <div class="av-shell">
        <!-- ============ SIDEBAR (desktop) ============ -->
        <aside class="av-side d-none d-lg-flex">
            <div class="frest-brand">
                <a href="{{ route('index') }}" class="frest-brand-link">
                    <img src="{{ asset(general()->logo()) }}" alt="{{ general()->title }}" class="frest-brand-img">
                    <img src="{{ asset(general()->favicon()) }}" alt="{{ general()->title }}" class="frest-brand-mini-icon">
                </a>
                <span class="frest-brand-badge">Seller</span>
            </div>
            <nav class="av-sidebar">
                @include(sellerTheme() . 'layouts.sidebar', ['promo' => true])
            </nav>
        </aside>

        <!-- ============ SIDEBAR (offcanvas, mobile/tablet) ============ -->
        <div class="offcanvas offcanvas-start av-offcanvas" tabindex="-1" id="avSidebar">
            <div class="offcanvas-header">
                <div class="frest-brand-left">
                    <a href="{{ route('index') }}" class="frest-brand-link">
                        <img src="{{ asset(general()->logo()) }}" alt="{{ general()->title }}" class="frest-brand-img">
                    </a>
                    <span class="frest-brand-badge">Seller</span>
                </div>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <nav class="av-sidebar">
                    @include(sellerTheme() . 'layouts.sidebar', ['promo' => false])
                </nav>
            </div>
        </div>

        <div class="av-body">
            <!-- ============ TOPBAR ============ -->
            <header class="av-topbar">
                <button class="av-icon-btn av-sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle Sidebar" title="Hide / Unhide Sidebar">
                    <i class="bx bx-menu"></i>
                </button>

                <form action="{{ route('seller.marketplace') }}" method="get" class="av-search-wrap">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" class="form-control" value="{{ request()->search }}"
                        placeholder="Search products, orders, buyers...">
                    <span class="search-shortcut d-none d-md-inline-block">Ctrl + /</span>
                    <select name="category" class="form-select d-none d-md-block">
                        <option value="">All Categories</option>
                        @foreach ($topCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ request()->category == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-search">Search</button>
                </form>

                <div class="av-top-actions">
                    <a href="{{ route('pageView', 'marketplace') }}" class="btn btn-outline-primary btn-sm d-none d-sm-inline-flex align-items-center gap-1" target="_blank">
                        <i class="bx bx-globe"></i> Marketplace
                    </a>

                    <a href="{{ route('seller.messages') }}" class="av-icon-btn d-none d-sm-inline-flex" title="Messages">
                        <i class="bx bx-chat"></i>
                        @if ($unreadMessages)
                            <span class="av-badge-dot">{{ $unreadMessages > 99 ? '99+' : $unreadMessages }}</span>
                        @endif
                    </a>

                    <a href="{{ route('seller.notifications') }}" class="av-icon-btn" title="Notifications">
                        <i class="bx bx-bell"></i>
                        @if ($unreadNotifications)
                            <span class="av-badge-dot">{{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}</span>
                        @endif
                    </a>

                    <!-- Frest Profile Dropdown -->
                    <div class="dropdown ms-1">
                        <a href="javascript:void(0);" class="frest-user-avatar" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ $authUser->imageFile ? route('imageView2', ['profile', $authUser->imageName(), 'w' => 80, 'h' => 80]) : asset($authUser->image()) }}" alt="{{ $authUser->name }}">
                            <span class="online-indicator"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li class="px-3 py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $authUser->imageFile ? route('imageView2', ['profile', $authUser->imageName(), 'w' => 80, 'h' => 80]) : asset($authUser->image()) }}" class="rounded-circle" width="36" height="36" alt="{{ $authUser->name }}">
                                    <div class="overflow-hidden">
                                        <div class="fw-semibold text-truncate">{{ $authUser->name }}</div>
                                        <small class="badge bg-label-primary">Seller</small>
                                    </div>
                                </div>
                            </li>
                            <li><a class="dropdown-item mt-1" href="{{ route($dashRole . '.dashboard') }}"><i class="bx bx-home-circle me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route($dashRole . '.profile') }}"><i class="bx bx-user me-2"></i>Edit Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route($dashRole . '.payments') }}"><i class="bx bx-credit-card me-2"></i>Billing & Payments</a></li>
                            <li><a class="dropdown-item" href="{{ route($dashRole . '.settings') }}"><i class="bx bx-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">@csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bx bx-power-off me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- ============ MAIN ============ -->
            <main class="av-main">
                <div class="av-page-head">
                    <div class="av-crumb">
                        <span class="av-crumb-icon"><i class="bx {{ $pageCrumb[2] }}"></i></span>
                        <a href="{{ route('seller.dashboard') }}" class="d-none d-sm-inline">Seller Portal</a>
                        <span class="sep d-none d-sm-inline"><i class="bx bx-chevron-right"></i></span>
                        <b>{{ $pageCrumb[1] }}</b>
                    </div>
                    <div class="av-page-actions">
                        @unless ($editingAd && $post->status == 'temp')
                            <a href="{{ route('seller.listingsAction', 'create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus me-1"></i> Post New Ad</a>
                        @endunless
                    </div>
                </div>

                <div class="av-page-body">
                    @yield('contents')
                </div>

                @include(sellerTheme() . 'layouts.footer')
            </main>
        </div>
    </div>

    @include('dashboard.scripts')
    @include('dashboard.partials.toasts')
    <script src="{{ asset(assetLinkSeller() . '/js/dashboard.js') }}"></script>
    @stack('js')
</body>

</html>

