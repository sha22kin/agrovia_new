@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{$page->seo_title?:websiteTitle($page->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$page->seo_title?:websiteTitle($page->name)}}" />
<meta name="description" property="og:description" content="{!!$page->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($page->image())}}" />
<meta name="url" property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('pageView',$page->slug?:'no-title')}}" />
@endsection @push('css')
@endpush @section('contents')

@php
  // Builds a marketplace link that keeps the current filters, with $changes applied (null removes one).
  $mpUrl = function (array $changes = []) {
      $params = array_merge(request()->only(['search', 'category', 'division', 'district', 'verified', 'sort']), $changes);
      $params = array_filter($params, fn ($v) => $v !== null && $v !== '');
      return url()->current() . ($params ? '?' . http_build_query($params) : '');
  };
  $categoryNames = $categories->pluck('name', 'id');
  $currentLocation = $district ? 'district-'.$district->id : ($division ? 'division-'.$division->id : '');

  // Applied filters, shown as removable chips above the results.
  $activeFilters = [];
  if ($keyword !== '') $activeFilters[] = ['label' => '"'.$keyword.'"', 'url' => $mpUrl(['search' => null])];
  if ($category) $activeFilters[] = ['label' => $category->name, 'url' => $mpUrl(['category' => null])];
  if ($district) $activeFilters[] = ['label' => $district->name, 'url' => $mpUrl(['district' => null, 'division' => $division->id])];
  elseif ($division) $activeFilters[] = ['label' => $division->name.' Division', 'url' => $mpUrl(['division' => null])];
  if ($verified) $activeFilters[] = ['label' => 'Verified sellers', 'url' => $mpUrl(['verified' => null])];
@endphp

<div class="mp-page">
<!-- ===== Page head ===== -->
<div class="container mp-head">
  <h1>Marketplace</h1>
  <p>Crops, livestock, equipment and farm inputs from sellers across Bangladesh.</p>

  <form action="{{ url()->current() }}" method="GET" class="agv-search mp-search">
    <i class="fa fa-search"></i>
    <input type="text" name="search" value="{{ $keyword }}" placeholder="Search products or sellers" aria-label="Search">
    <label class="agv-search-loc" aria-label="Location">
      <i class="fa fa-map-marker"></i>
      <select name="location">
        <option value="">All Bangladesh</option>
        @foreach($divisions as $div)
        <optgroup label="{{ $div->name }} Division">
          <option value="division-{{ $div->id }}" {{ $currentLocation == 'division-'.$div->id ? 'selected' : '' }}>All of {{ $div->name }}</option>
          @foreach($div->subDatas as $dist)
          <option value="district-{{ $dist->id }}" {{ $currentLocation == 'district-'.$dist->id ? 'selected' : '' }}>{{ $dist->name }}</option>
          @endforeach
        </optgroup>
        @endforeach
      </select>
    </label>
    @foreach(['category' => $category?->id, 'verified' => $verified ? 1 : null, 'sort' => $sort != 'newest' ? $sort : null] as $name => $value)
      @if($value)<input type="hidden" name="{{ $name }}" value="{{ $value }}">@endif
    @endforeach
    <button type="submit" class="btn-agv-solid">Search</button>
  </form>
</div>

<!-- ===== Body ===== -->
<div class="container agv-market mp-body">
  <div class="row g-4">

    <!-- Sidebar: always open on desktop, behind the Filters button on phones and tablets -->
    <div class="col-lg-3">
      <button type="button" class="agv-filter-toggle d-lg-none" aria-expanded="false" aria-controls="agvFilters">
        <span><i class="fa fa-sliders me-2"></i>Filters @if(count($activeFilters))<span class="mp-filter-count">{{ count($activeFilters) }}</span>@endif</span><i class="fa fa-angle-down"></i>
      </button>
      <aside class="agv-filters mp-filters" id="agvFilters">

        <div class="mp-filter-group">
          <h6>Category</h6>
          <ul class="mp-list">
            <li>
              <a href="{{ $mpUrl(['category' => null]) }}" class="{{ $category ? '' : 'active' }}">
                <span>All categories</span><em>{{ $totalAds }}</em>
              </a>
            </li>
            @foreach($categories as $ctg)
            <li>
              <a href="{{ $mpUrl(['category' => $ctg->id]) }}" class="{{ $category?->id == $ctg->id ? 'active' : '' }}">
                <span>{{ $ctg->name }}</span><em>{{ $adCounts[$ctg->id] ?? 0 }}</em>
              </a>
            </li>
            @endforeach
          </ul>
        </div>

        <div class="mp-filter-group">
          <h6>Location</h6>
          <ul class="mp-list">
            <li><a href="{{ $mpUrl(['division' => null, 'district' => null]) }}" class="{{ $division ? '' : 'active' }}"><span>All Bangladesh</span></a></li>
            @foreach($divisions as $div)
            @php $isOpen = $division?->id == $div->id; @endphp
            <li>
              <a href="{{ $mpUrl(['division' => $div->id, 'district' => null]) }}" class="{{ $isOpen && !$district ? 'active' : '' }} {{ $isOpen ? 'open' : '' }}">
                <span>{{ $div->name }}</span><i class="fa fa-angle-{{ $isOpen ? 'down' : 'right' }}"></i>
              </a>
              @if($isOpen)
              <ul class="mp-sublist">
                @foreach($districts as $dist)
                <li><a href="{{ $mpUrl(['division' => null, 'district' => $dist->id]) }}" class="{{ $district?->id == $dist->id ? 'active' : '' }}">{{ $dist->name }}</a></li>
                @endforeach
              </ul>
              @endif
            </li>
            @endforeach
          </ul>
        </div>

        <div class="mp-filter-group">
          <h6>Seller</h6>
          <label class="mp-switch" for="verifiedOnly">
            <span>Verified sellers only</span>
            <input type="checkbox" id="verifiedOnly" {{ $verified ? 'checked' : '' }}
              data-on="{{ $mpUrl(['verified' => 1]) }}" data-off="{{ $mpUrl(['verified' => null]) }}">
            <i aria-hidden="true"></i>
          </label>
        </div>
      </aside>
    </div>

    <!-- Listings -->
    <div class="col-lg-9">
      <div class="mp-results-bar">
        <div class="mp-count">
          <strong>{{ number_format($ads->total()) }}</strong> {{ Str::plural('listing', $ads->total()) }}
          @if($district || $division)
          <span>in {{ $district ? $district->name.', ' : '' }}{{ $division->name }}</span>
          @endif
        </div>
        <select class="mp-sort" id="agvSort" aria-label="Sort ads">
          @foreach($sorts as $key => $label)
          <option value="{{ $mpUrl(['sort' => $key == 'newest' ? null : $key]) }}" {{ $sort == $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      @if(count($activeFilters))
      <div class="mp-active">
        @foreach($activeFilters as $f)
        <a href="{{ $f['url'] }}" class="mp-chip" title="Remove filter">{{ $f['label'] }} <span aria-hidden="true">&times;</span></a>
        @endforeach
        <a href="{{ url()->current() }}" class="mp-clear">Clear all</a>
      </div>
      @endif

      @if($ads->isEmpty())
        <div class="mp-empty">
          <i class="fa fa-search"></i>
          <h6>No listings found</h6>
          <p>Try a different keyword or location, or clear some filters.</p>
          @if(count($activeFilters))<a href="{{ url()->current() }}" class="mp-clear">Clear all filters</a>@endif
        </div>
      @else
      <div class="row g-3">
        @foreach($ads as $ad)
        <div class="col-xl-4 col-6">
          @include(welcomeTheme().'services.includes.adCard', [
            'ad' => $ad,
            'categoryName' => $categoryNames[$ad->category_id] ?? null,
            'verified' => isset($verifiedSellers[$ad->addedby_id]),
          ])
        </div>
        @endforeach
      </div>

      @if($ads->hasPages())
      <div class="agv-pagination mt-4">
        {{ $ads->links('pagination::bootstrap-5') }}
      </div>
      @endif
      @endif
    </div>
  </div>
</div>
</div>

@endsection @push('js')
<script>
  // Sort and "Verified sellers only" reload the page with the new filter.
  document.getElementById('agvSort')?.addEventListener('change', function () { window.location = this.value; });
  document.getElementById('verifiedOnly').addEventListener('change', function () {
    window.location = this.checked ? this.dataset.on : this.dataset.off;
  });

  // Phones and tablets: the Filters button opens/closes the sidebar panels.
  document.querySelector('.agv-filter-toggle').addEventListener('click', function () {
    var open = document.getElementById('agvFilters').classList.toggle('open');
    this.classList.toggle('open', open);
    this.setAttribute('aria-expanded', open);
  });
</script>
@endpush
