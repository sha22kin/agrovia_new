{{-- Sidebar menu shared by the buyer and seller dashboards. Needs $dashRole; $promo shows the promo card. --}}
@php
  $isSeller = $dashRole == 'seller';
  // Like the approved design the dashboard is the profile overview, so "My Profile" is the active entry.
  $profileOpen = request()->routeIs($dashRole.'.dashboard',$dashRole.'.profile',$dashRole.'.changePassword');
@endphp

<a href="{{route($dashRole.'.dashboard')}}" class="av-nav-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
<a href="{{route('pageView','marketplace')}}" class="av-nav-link"><i class="fa-solid fa-store"></i> Marketplace</a>

@if($isSeller)
<a href="{{route('seller.listings')}}" class="av-nav-link {{request()->routeIs('seller.listings','seller.listingsAction')?'active':''}}"><i class="fa-regular fa-rectangle-list"></i> My Ads</a>
<a href="{{route('seller.listingsAction','create')}}" class="av-nav-link"><i class="fa-solid fa-circle-plus"></i> Post New Ad</a>
<a href="{{route('seller.payments')}}" class="av-nav-link {{request()->routeIs('seller.payments')?'active':''}}"><i class="fa-solid fa-credit-card"></i> Payments</a>
@else
<span class="av-nav-link disabled"><i class="fa-regular fa-file-lines"></i> My Orders <span class="soon">Soon</span></span>
<span class="av-nav-link disabled"><i class="fa-regular fa-comment"></i> My Enquiries <span class="soon">Soon</span></span>
<span class="av-nav-link disabled"><i class="fa-regular fa-heart"></i> My Wishlist <span class="soon">Soon</span></span>
@endif
<span class="av-nav-link disabled"><i class="fa-regular fa-message"></i> Messages <span class="soon">Soon</span></span>
<a href="{{route('pageView','community')}}" class="av-nav-link"><i class="fa-solid fa-users"></i> Community</a>
@if(!$isSeller && Auth::user()->isBuyer())
<form action="{{route('buyer.becomeSeller')}}" method="POST" onsubmit="return confirm('Activate your seller account so you can post ads?')">
  @csrf
  <button type="submit" class="av-nav-link av-nav-btn"><i class="fa-solid fa-store"></i> Become a Seller</button>
</form>
@endif

<a href="{{route($dashRole.'.dashboard')}}" class="av-nav-link {{$profileOpen?'active':''}} mt-2"><i class="fa-regular fa-circle-user"></i> My Profile</a>
<div class="av-sub-block">
  <a href="{{route($dashRole.'.dashboard')}}" class="av-sub-link {{request()->routeIs($dashRole.'.dashboard')?'active':''}}">Profile Overview</a>
  <a href="{{route($dashRole.'.profile').'#business'}}" class="av-sub-link">Business Info</a>
  <a href="{{route($dashRole.'.profile').'#address'}}" class="av-sub-link {{request()->routeIs($dashRole.'.profile')?'active':''}}">Addresses</a>
  <span class="av-sub-link disabled">Payment Methods</span>
  <a href="{{route($dashRole.'.changePassword')}}" class="av-sub-link {{request()->routeIs($dashRole.'.changePassword')?'active':''}}">Settings</a>
</div>

@if(!$isSeller)
<span class="av-nav-link disabled"><i class="fa-solid fa-magnifying-glass"></i> Saved Searches <span class="soon">Soon</span></span>
@endif
@if(Auth::user()->isAdmin())
<a href="{{route('admin.dashboard')}}" class="av-nav-link"><i class="fa-solid fa-user-shield"></i> Admin Panel</a>
@endif
<div class="av-divider"></div>
<form action="{{route('logout')}}" method="POST">
  @csrf
  <button type="submit" class="av-nav-link av-nav-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
</form>

@if($promo)
<div class="av-promo">
  <i class="fa-solid fa-tractor fig"></i>
  @if($isSeller)
  <h6>Sell More<br>With Agroviea</h6>
  <p>Post an ad with clear photos and reach buyers across Bangladesh.</p>
  <a href="{{route('seller.listingsAction','create')}}" class="btn btn-post"><i class="fa-solid fa-bullhorn me-1"></i> Post New Ad</a>
  @else
  <h6>Grow Your Business<br>With Agroviea</h6>
  <p>Post your buying requirement and get best sellers.</p>
  <a href="{{route('pageView','rfq')}}" class="btn btn-post"><i class="fa-solid fa-bullhorn me-1"></i> Post Requirement</a>
  @endif
</div>
@endif
