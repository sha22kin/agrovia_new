{{-- Expert sidebar: Frest eCommerce Admin Template Navigation --}}
@php
    $count = fn ($n) => $n > 99 ? '99+' : $n;

    $appLinks = [
        [route('expert.dashboard'), 'bx-home-circle', 'Dashboard', request()->routeIs('expert.dashboard'), 0],
        [route('expert.consultations'), 'bx-calendar-check', 'Consultations', request()->routeIs('expert.consultations'), 0],
        [route('expert.qa'), 'bx-help-circle', 'Ask an Expert (Q&A)', request()->routeIs('expert.qa'), 0],
        [route('expert.articles'), 'bx-news', 'My Articles', request()->routeIs('expert.articles', 'expert.articleAction'), 0],
        [route('expert.availability'), 'bx-time-five', 'Availability', request()->routeIs('expert.availability'), 0],
        [route('expert.earnings'), 'bx-wallet', 'Earnings', request()->routeIs('expert.earnings'), 0],
    ];

    $commLinks = [
        [route('expert.messages'), 'bx-chat', 'Messages', request()->routeIs('expert.messages'), $unreadMessages],
        [route('expert.community'), 'bx-group', 'Community', request()->routeIs('expert.community', 'expert.communityAction'), 0],
    ];
@endphp

{{-- Category 1: EXPERT SERVICES --}}
<div class="menu-header">Services &amp; Schedule</div>
@foreach ($appLinks as [$url, $icon, $label, $active, $badge])
    <a href="{{ $url }}" class="av-nav-link {{ $active ? 'active' : '' }}" title="{{ $label }}">
        <i class="bx {{ $icon }}"></i>
        <span class="label">{{ $label }}</span>
        @if ($badge)
            <span class="pill ms-auto">{{ $count($badge) }}</span>
        @endif
    </a>
@endforeach

{{-- Category 2: CONNECT & COMMUNITY --}}
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

<a href="{{ route('expert.profile') }}" class="av-nav-link {{ request()->routeIs('expert.profile') ? 'active' : '' }}" title="My Profile">
    <i class="bx bx-user-circle"></i>
    <span class="label">My Profile</span>
</a>

<a href="{{ route('expert.settings') }}" class="av-nav-link {{ request()->routeIs('expert.settings', 'expert.changePassword') ? 'active' : '' }}" title="Settings">
    <i class="bx bx-cog"></i>
    <span class="label">Settings</span>
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
        <h6>Share Your Expertise</h6>
        <p>Answer farmer questions, publish articles, and grow your reach across the network.</p>
        <a href="{{ route('expert.articleAction', 'create') }}" class="btn-post text-decoration-none">
            <i class="bx bx-plus me-1"></i> Write an Article
        </a>
    </div>
@endif
