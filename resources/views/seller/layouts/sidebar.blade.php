{{-- Seller sidebar: Frest eCommerce Admin Template Navigation --}}
@php
    $sid = $promo ? 'd' : 'm';
    // "Post New Ad" opens the edit page of a temp ad, so that page belongs to "Post New Ad", not "My Ads".
    $postingAd = request()->routeIs('seller.listingsAction') && isset($post) && $post->status == 'temp';
    $myAdsOpen = request()->routeIs('seller.listings', 'seller.listingsAction') && !$postingAd;
    $count = fn ($n) => $n > 99 ? '99+' : $n;

    $appLinks = [
        [route('seller.dashboard'), 'bx-home-circle', 'Dashboard', request()->routeIs('seller.dashboard'), 0],
        [route('seller.marketplace'), 'bx-store-alt', 'Marketplace', request()->routeIs('seller.marketplace', 'seller.marketplaceView'), 0],
        [route('seller.listingsAction', 'create'), 'bx-plus-circle', 'Post New Ad', $postingAd, 0],
        [route('seller.listings'), 'bx-list-ul', 'My Ads', $myAdsOpen, 0],
        [route('seller.orders'), 'bx-receipt', 'Orders', request()->routeIs('seller.orders'), 0],
        [route('seller.enquiries'), 'bx-briefcase-alt-2', 'Buyer Requirements', request()->routeIs('seller.enquiries', 'seller.enquiriesAction'), 0],
    ];

    $commLinks = [
        [route('seller.messages'), 'bx-chat', 'Messages', request()->routeIs('seller.messages'), $unreadMessages],
        [route('seller.notifications'), 'bx-bell', 'Notifications', request()->routeIs('seller.notifications'), $unreadNotifications],
        [route('seller.payments'), 'bx-credit-card', 'Payments', request()->routeIs('seller.payments'), 0],
        [route('seller.wishlist'), 'bx-heart', 'My Wishlist', request()->routeIs('seller.wishlist'), 0],
        [route('seller.community'), 'bx-group', 'Community', request()->routeIs('seller.community', 'seller.communityAction'), 0],
    ];
@endphp

{{-- Category 1: STORE & APPS --}}
<div class="menu-header">Store &amp; Apps</div>
@foreach ($appLinks as [$url, $icon, $label, $active, $badge])
    <a href="{{ $url }}" class="av-nav-link {{ $active ? 'active' : '' }}" title="{{ $label }}">
        <i class="bx {{ $icon }}"></i>
        <span class="label">{{ $label }}</span>
        @if ($badge)
            <span class="pill ms-auto">{{ $count($badge) }}</span>
        @endif
    </a>
@endforeach

{{-- Category 2: CONNECT & ACTIVITY --}}
<div class="menu-header">Communication</div>
@foreach ($commLinks as [$url, $icon, $label, $active, $badge])
    <a href="{{ $url }}" class="av-nav-link {{ $active ? 'active' : '' }}" title="{{ $label }}">
        <i class="bx {{ $icon }}"></i>
        <span class="label">{{ $label }}</span>
        @if ($badge)
            <span class="pill ms-auto">{{ $count($badge) }}</span>
        @endif
    </a>
@endforeach

{{-- Category 3: ACCOUNT & SETTINGS --}}
<div class="menu-header">Account</div>

<a href="{{ route('seller.profileOverview') }}" class="av-nav-link {{ request()->routeIs('seller.profileOverview', 'seller.profile', 'seller.businessInfo', 'seller.addresses', 'seller.paymentMethods') ? 'active' : '' }}" title="My Profile">
    <i class="bx bx-user-circle"></i>
    <span class="label">My Profile</span>
</a>

<a href="{{ route('seller.settings') }}" class="av-nav-link {{ request()->routeIs('seller.settings', 'seller.changePassword') ? 'active' : '' }}" title="Settings">
    <i class="bx bx-cog"></i>
    <span class="label">Settings</span>
</a>

<a href="{{ route('seller.savedSearches') }}"
    class="av-nav-link {{ request()->routeIs('seller.savedSearches') ? 'active' : '' }}" title="{{ __('Saved Searches') }}">
    <i class="bx bx-search-alt"></i>
    <span class="label">{{ __('Saved Searches') }}</span>
</a>

@if (Auth::user()->isAdmin())
    <a href="{{ route('admin.dashboard') }}" class="av-nav-link" title="{{ __('Admin Panel') }}">
        <i class="bx bx-shield-quarter"></i>
        <span class="label">{{ __('Admin Panel') }}</span>
    </a>
@endif

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="av-nav-link av-nav-btn text-danger" title="Logout">
        <i class="bx bx-log-out text-danger"></i>
        <span class="label">Logout</span>
    </button>
</form>

@if ($promo)
    <div class="av-promo">
        <h6>Sell more with {{ general()->title ?: 'Agroviea' }}</h6>
        <p>Post clear photos and reach verified buyers across Bangladesh.</p>
        <a href="{{ route('seller.listingsAction', 'create') }}" class="btn-post text-decoration-none">
            <i class="bx bx-plus me-1"></i> Post New Ad
        </a>
    </div>
@endif


