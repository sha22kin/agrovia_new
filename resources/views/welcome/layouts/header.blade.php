@php
  // Everything of the header comes from the admin settings: logo, contact, social links and the "Header Menus" menu.
  $site = general();
  $phone = trim((string) $site->mobile);
  $email = trim((string) $site->email);
  $socials = collect([
      ['Facebook', 'fa-facebook-f', $site->facebook_link],
      ['Twitter', 'fa-twitter', $site->twitter_link],
      ['Instagram', 'fa-instagram', $site->instagram_link],
      ['YouTube', 'fa-youtube', $site->youtube_link],
      ['LinkedIn', 'fa-linkedin', $site->linkedin_link],
      ['Pinterest', 'fa-pinterest', $site->pinterest_link],
  ])->filter(fn ($item) => trim((string) $item[2]) !== '' && ! str_starts_with(trim((string) $item[2]), '#'));
  $account = Auth::user();
@endphp

@if($phone !== '' || $email !== '' || $socials->isNotEmpty())
<!-- =========================
     TOP HEADER
========================= -->
<div class="top-header">
    <div class="container">
        <div class="row align-items-center">

            <!-- Left: Contact Information -->
            <div class="col-lg-6 col-md-7">
                <div class="top-contact">

                    @if($phone !== '')
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">
                        <i class="fa fa-phone"></i>
                        {{ $phone }}
                    </a>
                    @endif

                    @if($email !== '')
                    <a href="mailto:{{ $email }}">
                        <i class="fa fa-envelope"></i>
                        {{ $email }}
                    </a>
                    @endif

                </div>
            </div>

            <!-- Right: Social Links -->
            <div class="col-lg-6 col-md-5">
                <div class="top-social">

                    @foreach($socials as [$socialName, $socialIcon, $socialLink])
                    <a href="{{ $socialLink }}" target="_blank" rel="noopener" aria-label="{{ $socialName }}">
                        <i class="fa {{ $socialIcon }}"></i>
                    </a>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>
@endif



<header class="headerMain d-lg-block d-none">
  <div class="container">
    <div class="row">
      <div class="col-auto logo">
        <!--<div class="logo-icon">🌿</div>-->
        <div class="logo-text">
            <a href="{{route('index')}}">  <img src="{{asset($site->logo())}}" alt="{{ $site->title }}" /></a>
          <!--<div class="name">Agroviea</div>-->
          <!--<div class="tagline">FROM FARM TO FAIR MARKET</div>-->
        </div>
      </div>
      <nav class="col-auto">
          
          
           @if(menu('Header Menus'))
            <div class="nav-links">
                @foreach(menu('Header Menus')->subMenus as $menu)
                    <a href="{{ asset($menu->menuLink()) }}"
                       class="{{ request()->url() == asset($menu->menuLink()) ? 'active' : '' }}">
                        {{ $menu->menuName() }}
                    </a>
                @endforeach
            </div>
        @endif
        
        
<!-- Language Toggle -->
<div class="lang" id="languageToggle">
    <span id="languageText">English</span>
</div>

<!-- Google Translate Container -->
<div id="google_translate_element"></div>
        
        
        
        <div class="header-actions">
          @if($account)
            <a class="btn-sell" href="{{ dashboardUrl() }}">{{ $account->dashboardLabel() }}</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn-login">Logout</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn-register"><i class="fa fa-user-plus"></i> Register</a>
          @endif
        </div>
      </nav>
    </div>
  </div>
</header>








<!-- Mobile Header -->
  <header class="mobile-header d-lg-none d-flex">
    <a href="{{route('index')}}">  <img src="{{asset($site->logo())}}" alt="{{ $site->title }}" /></a>
    <div class="menu-btn" id="openMenu">
      <i class="fa fa-bars"></i>
    </div>
  </header>

  <!-- Outside Overlay Click Area -->
  <div class="overlay" id="overlay"></div>

  <!-- Sidebar Menu (Right Side) -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <a href="#" class="logo"><i class="fa fa-cubes"></i> Menu</a>
      <div class="close-btn" id="closeMenu">
        <i class="fa fa-times"></i>
      </div>
    </div>

        <ul class="sidebar-menu">
          @if(menu('Header Menus'))
            @foreach(menu('Header Menus')->subMenus as $menu)
              @if($menu->subMenus->count() > 0)
                {{-- Submenu ache amon parent menu items --}}
                <li class="has-submenu">
                  <a href="javascript:void(0)">
                    <span>
                     
                      {{ $menu->menuName() }}
                    </span>
                    <i class="fa fa-plus toggle-icon"></i>
                  </a>
                  <ul class="submenu">
                    @foreach($menu->subMenus as $subMenu)
                      <li>
                        <a href="{{ asset($subMenu->menuLink()) }}">
                          {{ $subMenu->menuName() }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @else
                {{-- Single menu items (no submenu) --}}
                <li>
                  <a href="{{ asset($menu->menuLink()) }}">
                    <span>
                  
                      {{ $menu->menuName() }}
                    </span>
                  </a>
                </li>
              @endif
            @endforeach
          @endif
        </ul>

        <div class="sidebar-account">
          @if($account)
            <a class="btn-sell" href="{{ dashboardUrl() }}">{{ $account->dashboardLabel() }}</a>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn-login">Logout</button>
            </form>
          @else
            <a href="{{ route('login') }}" class="btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn-register"><i class="fa fa-user-plus"></i> Register</a>
          @endif
        </div>

  </aside>




