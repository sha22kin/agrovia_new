{{-- Shared by the buyer and seller dashboards (rendered by dashboard/page.blade.php). Needs $role. --}}
@php
  $seller = $ad->user;
  $enquireUrl = $role == 'buyer' ? route('buyer.enquiriesAction',['action'=>'create','title'=>'Enquiry about '.Str::limit($ad->name,150,''),'category'=>$ad->category_id]) : null;
  $isMine = $ad->addedby_id == Auth::id();
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">Marketplace</h5>
  <a href="{{route($role.'.marketplace')}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to Marketplace</a>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="av-card mt-0">
      <div class="av-ad-main"><img id="adMainImage" src="{{asset($ad->fiImage())}}" alt="{{$ad->name}}" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'"></div>
      @if($ad->galleryFiles->count() > 1)
      <div class="d-flex gap-2 flex-wrap mt-2">
        @foreach($ad->galleryFiles as $file)
        <img src="{{asset($file->image())}}" alt="{{$ad->name}}" class="av-ad-thumb {{$loop->first?'active':''}}" data-full="{{asset($file->image())}}">
        @endforeach
      </div>
      @endif

      <h5 class="fw-bold mt-3 mb-1">{{$ad->name}}</h5>
      <div class="text-muted" style="font-size:.8rem;">
        Posted {{$ad->created_at?->format('M d, Y')}}
        @if($ad->category) &middot; {{$ad->category->name}}@endif
        @if($ad->area()) &middot; <i class="fa-solid fa-location-dot"></i> {{$ad->area()}}@endif
      </div>
      <div class="av-product-price mt-2" style="font-size:1.25rem;">{{priceFullFormat($ad->price)}}@if($ad->negotiable) <span class="text-muted fw-normal" style="font-size:.78rem;">Negotiable</span>@endif</div>
      <hr>
      <div class="text-secondary" style="font-size:.88rem;">{!! $ad->description ? nl2br(e($ad->description)) : 'The seller did not add any details.' !!}</div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="av-mini-card mt-0">
      <div class="av-card-title"><i class="fa-solid fa-shop"></i> {{$isMine ? 'Your ad' : 'Seller'}}</div>
      <div class="d-flex align-items-center gap-2 mt-2">
        <img src="{{$seller?->avatar(80)}}" alt="{{$seller?->name}}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
        <div>
          <div class="fw-semibold" style="font-size:.9rem;">{{$seller?->name ?: 'Seller'}}</div>
          <div class="text-muted" style="font-size:.74rem;">@if($seller?->company_name){{$seller->company_name}} &middot; @endif{{$seller?->created_at ? 'Member since '.$seller->created_at->format('M Y') : ''}}</div>
        </div>
      </div>
      <div class="mt-3" style="font-size:.84rem;"><i class="fa-solid fa-phone me-2 text-success"></i>
        @if($ad->hide_phone || !$ad->phone_number)Phone number is hidden by the seller @else{{$ad->phone_number}}@endif
      </div>

      <div class="d-grid gap-2 mt-3">
        @if($role == 'buyer')
        <a href="{{$enquireUrl}}" class="btn-av-primary text-center"><i class="fa-solid fa-paper-plane me-2"></i>Enquire Now</a>
        @if($ad->addedby_id)
        <a href="{{route('buyer.messages',['to'=>$ad->addedby_id,'ad'=>$ad->id])}}" class="btn-av-outline text-center"><i class="fa-regular fa-comments me-2"></i>Chat with Seller</a>
        @endif
        @elseif($isMine)
        <a href="{{route('seller.listingsAction',['edit',$ad->id])}}" class="btn-av-primary text-center"><i class="fa-solid fa-pen me-2"></i>Edit Your Ad</a>
        @endif
        @unless($isMine)
        <form action="{{route($role.'.wishlistToggle',$ad->id)}}" method="POST">
          @csrf
          <button type="submit" class="btn-av-outline w-100"><i class="fa-{{$saved?'solid':'regular'}} fa-heart me-2" @if($saved) style="color:#e0574c;" @endif></i>{{$saved?'Saved to Wishlist':'Save to Wishlist'}}</button>
        </form>
        @endunless
      </div>
    </div>

    <div class="av-mini-card">
      <div class="av-card-title"><i class="fa-solid fa-shield-halved"></i> Stay Alert</div>
      <p class="text-muted mt-2 mb-0" style="font-size:.78rem;">Never share card details or OTPs, and check the goods in person before you pay.</p>
    </div>
  </div>
</div>

@if($related->count())
<h6 class="fw-bold mt-4 mb-2">Similar Ads</h6>
<div class="row g-3">
  @foreach($related as $item)
  <div class="col-6 col-md-4 col-xl-3">
    <div class="av-product-card">
      <a href="{{route($role.'.marketplaceView',$item->id)}}"><img src="{{asset($item->fiImage())}}" alt="{{$item->name}}" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'"></a>
      <div class="av-product-body">
        <div class="av-product-name"><a href="{{route($role.'.marketplaceView',$item->id)}}">{{Str::limit($item->name,40)}}</a></div>
        <div class="av-product-seller"><i class="fa-solid fa-shop me-1"></i>{{$item->user?->name ?: '—'}}</div>
        <div class="av-product-price">{{priceFullFormat($item->price)}}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif


@push('js')
<script>
  // A small photo shows in the big frame when it is clicked.
  document.querySelectorAll('.av-ad-thumb').forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      document.getElementById('adMainImage').src = thumb.dataset.full;
      document.querySelectorAll('.av-ad-thumb').forEach(function (t) { t.classList.remove('active'); });
      thumb.classList.add('active');
    });
  });
</script>
@endpush
