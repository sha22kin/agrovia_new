{{-- Shared by the buyer and seller dashboards (rendered by dashboard/page.blade.php). Needs $role. --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">My Wishlist</h5>
  <span class="text-muted" style="font-size:.82rem;">{{number_format($items->total())}} saved {{$items->total()==1?'item':'items'}}</span>
</div>

<div class="row g-3 mt-1">
  @forelse($items as $item)
  @php $ad = $item->post; @endphp
  <div class="col-6 col-md-4 col-xl-3">
    <div class="av-wish-card">
      <form action="{{route($role.'.wishlistToggle',$ad->id)}}" method="POST">
        @csrf
        <button type="submit" class="av-wish-heart" title="Remove from wishlist"><i class="fa-solid fa-heart"></i></button>
      </form>
      <a href="{{route($role.'.marketplaceView',$ad->id)}}">
        <img src="{{asset($ad->fiImage())}}" alt="{{$ad->name}}" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'">
      </a>
      <div class="av-product-body">
        <div class="av-product-name"><a href="{{route($role.'.marketplaceView',$ad->id)}}">{{Str::limit($ad->name,40)}}</a></div>
        <div class="av-product-seller">{{$ad->user?->name ?: '—'}}</div>
        <div class="av-product-price">{{priceFullFormat($ad->price)}}@if($ad->negotiable) <small class="text-muted fw-normal">Negotiable</small>@endif</div>
        @if($role=='buyer')
        <a href="{{route($role.'.enquiriesAction',['action'=>'create','title'=>'Enquiry about '.Str::limit($ad->name,150,''),'category'=>$ad->category_id])}}" class="btn-av-primary btn-sm mt-1">Enquire Now</a>
        @endif
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="av-card mt-0">
      <div class="av-empty"><i class="fa-regular fa-heart"></i>No saved items yet. <a href="{{route($role.'.marketplace')}}">Browse the marketplace</a> and tap "Save to wishlist" on an ad.</div>
    </div>
  </div>
  @endforelse
</div>

{{$items->links('dashboard.partials.pagination')}}

