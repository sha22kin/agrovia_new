{{-- Buyer sidebar: Frest eCommerce Admin Template Navigation --}}
@php
    $count = fn ($n) => $n > 99 ? '99+' : $n;

    $appLinks = [
        [route('buyer.dashboard'), 'bx-home-circle', 'Dashboard', request()->routeIs('buyer.dashboard'), 0],
        [route('buyer.marketplace'), 'bx-store-alt', 'Marketplace', request()->routeIs('buyer.marketplace', 'buyer.marketplaceView'), 0],
        [route('buyer.orders'), 'bx-receipt', 'My Orders', request()->routeIs('buyer.orders'), 0],
        [route('buyer.enquiries'), 'bx-briefcase-alt-2', 'My Enquiries', request()->routeIs('buyer.enquiries', 'buyer.enquiriesAction'), 0],
        [route('buyer.wishlist'), 'bx-heart', 'My Wishlist', request()->routeIs('buyer.wishlist'), 0],
        [route('buyer.ads'), 'bx-list-ul', 'My Ads', request()->routeIs('buyer.ads', 'buyer.adsAction'), 0],
    ];

    $commLinks = [
        [route('buyer.messages'), 'bx-chat', 'Messages', request()->routeIs('buyer.messages'), $unreadMessages],
        [route('buyer.community'), 'bx-group', 'Community', request()->routeIs('buyer.community', 'buyer.communityAction'), 0],
    ];
@endphp

{{-- Category 1: SHOP & ORDERS --}}
<div class="menu-header">Shop &amp; Orders</div>
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

<a href="{{ route('buyer.profileOverview') }}" class="av-nav-link {{ request()->routeIs('buyer.profileOverview', 'buyer.profile', 'buyer.preferences', 'buyer.addresses', 'buyer.paymentMethods', 'buyer.businessInfo', 'buyer.businessDocumentAction') ? 'active' : '' }}" title="My Profile">
    <i class="bx bx-user-circle"></i>
    <span class="label">My Profile</span>
</a>

<a href="{{ route('buyer.settings') }}" class="av-nav-link {{ request()->routeIs('buyer.settings', 'buyer.changePassword') ? 'active' : '' }}" title="Settings">
    <i class="bx bx-cog"></i>
    <span class="label">Settings</span>
</a>

<a href="{{ route('buyer.savedSearches') }}"
    class="av-nav-link {{ request()->routeIs('buyer.savedSearches') ? 'active' : '' }}" title="{{ __('Saved Searches') }}">
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
        <h6>Grow with {{ general()->title ?: 'Agroviea' }}</h6>
        <p>Post your buying requirement and get best quotes from verified sellers.</p>
        <a href="{{ route('buyer.enquiriesAction', 'create') }}" class="btn-post text-decoration-none">
            <i class="bx bx-plus me-1"></i> Post Requirement
        </a>
    </div>
@endif
