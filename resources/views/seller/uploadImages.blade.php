{{-- Photos of the ad: Frest style photo uploader
     Main photo field takes one photo (replaces current one); More photos field takes several.
     Re-rendered after every upload / delete / change (ajax). --}}
@php
  $photos = $post->galleryFiles;
  $main = $photos->first();
  $more = $photos->slice(1)->values();
  $slots = 9;
  $uploadUrl = route('seller.listingsAction', ['upload-image', $post->id]);
@endphp

<div class="ph-block mb-3">
  <div class="ph-title">
    <span>Main photo <span class="text-danger">*</span></span>
    @if($main)
      <span class="badge bg-label-success">Uploaded</span>
    @endif
  </div>
  <input type="file" class="form-control uploadImageFile" data-main="1" accept="image/*,.heic,.heif" data-url="{{$uploadUrl}}">
  @if($main)
  <div class="ph-list">
    <div class="ph-item main">
      <img src="{{asset($main->file_url)}}" alt="Main photo">
      <span class="ph-actions">
        <button type="button" class="js-ph-del" data-url="{{route('seller.listingsAction',['delete-image',$post->id,'image_id'=>$main->id])}}" title="Delete">
          <i class="bx bx-trash"></i>
        </button>
      </span>
    </div>
  </div>
  @else
  <div class="ph-none">No main photo yet. Upload a high-quality cover photo.</div>
  @endif
  @if ($errors->has('photos'))<div class="invalid-feedback d-block">{{ $errors->first('photos') }}</div>@endif
</div>

<div class="ph-block">
  <div class="ph-title">
    <span>Gallery photos</span>
    <small>({{$more->count()}}/{{$slots}})</small>
  </div>
  <input type="file" class="form-control uploadImageFile" data-main="0" accept="image/*,.heic,.heif" data-url="{{$uploadUrl}}" multiple {{$main && $more->count() < $slots ? '' : 'disabled'}}>
  @if($more->isNotEmpty())
  <div class="ph-list">
    @foreach($more as $image)
    <div class="ph-item">
      <img src="{{asset($image->file_url)}}" alt="Gallery photo">
      <span class="ph-actions">
        <button type="button" class="js-ph-main" data-url="{{route('seller.listingsAction',['main-image',$post->id,'image_id'=>$image->id])}}" title="Make main photo">
          <i class="bx bxs-star"></i>
        </button>
        <button type="button" class="js-ph-del" data-url="{{route('seller.listingsAction',['delete-image',$post->id,'image_id'=>$image->id])}}" title="Delete">
          <i class="bx bx-trash"></i>
        </button>
      </span>
    </div>
    @endforeach
  </div>
  @else
  <div class="ph-none">{{$main ? 'Optional: Add other angles, packaging, certifications.' : 'Upload the main photo first.'}}</div>
  @endif
</div>

