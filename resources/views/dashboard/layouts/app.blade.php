@php
  // The same UI serves both dashboards; only the menu, stats and cards differ per role.
  $dashRole = request()->routeIs('seller.*') ? 'seller' : 'buyer';
  $authUser = Auth::user();
  $topCategories = \App\Models\Attribute::where('type',0)->where('status','active')->whereNull('parent_id')->orderBy('name')->get(['id','name']);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{csrf_token()}}">
  @yield('title')
  <link rel="shortcut icon" type="image/x-icon" href="{{asset(general()->favicon())}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('public/dashboard/css/dashboard.css')}}">
  @stack('theme-css')
  @stack('css')
</head>
<body class="av-dashboard">

<!-- ============ TOPBAR ============ -->
<header class="av-topbar py-2">
  <div class="container-fluid px-3">
    <div class="row align-items-center gy-2">
      <div class="col-6 col-lg-2 d-flex align-items-center gap-2">
        <button class="btn btn-light border d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#avSidebar" aria-label="Menu">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="av-logo-text d-none d-sm-block">
          <a href="{{route('index')}}"><img src="{{asset(general()->logo())}}" alt="{{general()->title}}" /></a>
        </div>
      </div>

      <div class="col-12 col-lg-6 order-3 order-lg-2">
        <form action="{{route('search')}}" method="get" class="av-search-wrap d-flex align-items-center">
          <input type="text" name="search" class="form-control" value="{{request()->search}}" placeholder="Search crops, seeds, fertilizers, machinery...">
          <select name="category" class="form-select d-none d-md-block">
            <option value="">All Categories</option>
            @foreach($topCategories as $category)
            <option value="{{$category->id}}" {{request()->category==$category->id?'selected':''}}>{{$category->name}}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-search" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
      </div>

      <div class="col-6 col-lg-4 order-2 order-lg-3">
        <div class="d-flex align-items-center justify-content-end gap-2">
          <span class="av-icon-btn disabled d-none d-sm-flex" title="Messages: coming soon"><i class="fa-regular fa-comment-dots"></i></span>
          <span class="av-icon-btn disabled" title="Notifications: coming soon"><i class="fa-regular fa-bell"></i></span>
          <div class="dropdown">
            <a href="#" class="av-user-chip d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{$authUser->imageFile?route('imageView2',['profile',$authUser->imageName(),'w'=>80,'h'=>80]):asset($authUser->image())}}" alt="{{$authUser->name}}">
              <span class="d-none d-md-block">
                <span class="uname d-block">{{Str::limit($authUser->name,18)}}</span>
                <span class="urole d-block">{{ucfirst($dashRole)}}</span>
              </span>
              <i class="fa-solid fa-chevron-down d-none d-md-block small text-muted"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{route($dashRole.'.dashboard')}}"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a></li>
              <li><a class="dropdown-item" href="{{route($dashRole.'.profile')}}"><i class="fa-regular fa-user me-2"></i>Edit Profile</a></li>
              <li><a class="dropdown-item" href="{{route($dashRole.'.changePassword')}}"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{route('logout')}}" method="POST">@csrf
                  <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">

    <!-- ============ SIDEBAR (desktop) ============ -->
    <aside class="col-lg-3 col-xl-2 d-none d-lg-block px-0">
      <nav class="av-sidebar">
        @include('dashboard.layouts.nav',['promo'=>true])
      </nav>
    </aside>

    <!-- ============ SIDEBAR (offcanvas mobile) ============ -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="avSidebar">
      <div class="offcanvas-header border-bottom">
        <span class="av-logo-text"><a href="{{route('index')}}"><img src="{{asset(general()->logo())}}" alt="{{general()->title}}" style="max-height:34px"></a></span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body p-2">
        <nav class="av-sidebar" style="min-height:auto;">
          @include('dashboard.layouts.nav',['promo'=>false])
        </nav>
      </div>
    </div>

    <!-- ============ MAIN ============ -->
    <main class="col-lg-9 col-xl-10 av-main">
      @include('dashboard.alerts')
      @yield('contents')
    </main>
  </div>
</div>

@include('dashboard.scripts')
<script src="{{asset('public/dashboard/js/dashboard.js')}}"></script>
@stack('js')
</body>
</html>
