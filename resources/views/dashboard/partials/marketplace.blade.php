{{-- Shared by the buyer and seller dashboards (rendered by dashboard/page.blade.php). Needs $role. --}}
@push('css')
<link rel="stylesheet" href="{{asset('public/welcome/css/adCard.css')}}">
<style>
  .av-mp-head { display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:12px 20px; margin-bottom:18px; }
  .av-mp-head h5 { margin:0 0 2px; }
  .av-mp-head p { margin:0; font-size:.85rem; }
  .av-mp-count { font-size:.8rem; color:#6c757d; margin-left:6px; font-weight:400; }
  .av-mp-cat { display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid #dfe7e0; border-radius:8px; padding:0 10px 0 12px; color:#6d7d70; margin:0; }
  .av-mp-cat:focus-within { border-color:#9cc5a6; }
  .av-mp-cat select { border:none; outline:none; background:transparent; font-size:.85rem; color:#253227; padding:8px 4px; min-width:200px; cursor:pointer; }
  @media (max-width:575.98px) { .av-mp-cat { width:100%; } .av-mp-cat select { flex:1; min-width:0; } }
</style>
@endpush

@php
  // The category dropdown keeps the search, location and price the user already chose.
  $carry = collect(['search'=>$keyword ?: null,'district'=>$district?->id,'min_price'=>$min,'max_price'=>$max])->filter(fn ($v) => $v !== null && $v !== '')->all();
  $filtered = $carry !== [];
  $categoryNames = $categories->pluck('name','id');
@endphp

<div class="av-mp-head">
  <div>
    <h5 class="fw-bold">Marketplace <span class="av-mp-count">{{number_format($ads->total())}} {{$ads->total()==1?'ad':'ads'}}</span></h5>
    <p class="text-secondary">Browse crops, seeds, fertilizers and machinery from verified sellers.</p>
  </div>
  <label class="av-mp-cat">
    <span class="visually-hidden">Category</span>
    <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
    <select onchange="window.location=this.value">
      <option value="{{route($role.'.marketplace',$carry)}}">All categories</option>
      @foreach($categories as $item)
      <option value="{{route($role.'.marketplace',$carry + ['category'=>$item->id])}}" {{$category && $category->id==$item->id?'selected':''}}>{{$item->name}}</option>
      @endforeach
    </select>
  </label>
</div>

@if($filtered)
<div class="d-flex flex-wrap align-items-center gap-2 mb-3" style="font-size:.82rem;">
  <span class="text-muted">Filtered by</span>
  @if($keyword!=='')<span class="av-post-tag">Search: {{$keyword}}</span>@endif
  @if($district)<span class="av-post-tag">Location: {{$district->name}}</span>@endif
  @if($min!==null && $max!==null)<span class="av-post-tag">Price: {{priceFullFormat($min)}} - {{priceFullFormat($max)}}</span>
  @elseif($max!==null)<span class="av-post-tag">Price: up to {{priceFullFormat($max)}}</span>
  @elseif($min!==null)<span class="av-post-tag">Price: from {{priceFullFormat($min)}}</span>@endif
  <a href="{{route($role.'.marketplace')}}" class="av-link-more">Clear all</a>
  @if($keyword!=='')
  <form action="{{route($role.'.savedSearchAction','save')}}" method="POST" class="ms-auto">
    @csrf
    <input type="hidden" name="keyword" value="{{$keyword}}">
    <input type="hidden" name="category" value="{{$category?->id}}">
    <input type="hidden" name="district" value="{{$district?->id}}">
    <input type="hidden" name="min_price" value="{{$min}}">
    <input type="hidden" name="max_price" value="{{$max}}">
    <button type="submit" class="btn-av-outline btn-sm py-1 px-2"><i class="fa-solid fa-magnifying-glass me-1"></i>Save this search</button>
  </form>
  @endif
</div>
@endif

<div class="row g-3">
  @forelse($ads as $ad)
  <div class="col-6 col-md-4 col-xl-3">
    @include(welcomeTheme().'services.includes.adCard', [
      'ad' => $ad,
      'categoryName' => $categoryNames[$ad->category_id] ?? null,
      'verified' => isset($verifiedSellers[$ad->addedby_id]),
      'url' => route($role.'.marketplaceView',$ad->id),
      'enquireUrl' => $role=='buyer' ? route($role.'.enquiriesAction',['action'=>'create','title'=>'Enquiry about '.Str::limit($ad->name,150,''),'category'=>$ad->category_id]) : null,
    ])
  </div>
  @empty
  <div class="col-12">
    <div class="av-card mt-0">
      <div class="av-empty"><i class="fa-solid fa-store"></i>No ads found{{$category?' in '.$category->name:''}}. @if($category || $filtered)<a href="{{route($role.'.marketplace')}}">Show all ads</a>@endif</div>
    </div>
  </div>
  @endforelse
</div>

{{$ads->links('dashboard.partials.pagination')}}

