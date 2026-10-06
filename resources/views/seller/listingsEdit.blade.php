@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle($post->status=='temp'?'Post New Ad':'Edit Ad')}}</title>
@endsection

@section('contents')
@php
  $isNew = $post->status == 'temp';
  $checkedSubs = array_map('intval', (array) old('subcategories', $selectedSubs));
  $submitted = old('_token') !== null;
  $negotiable = $submitted ? old('negotiable') : $post->negotiable;
  $hidePhone = $submitted ? old('hide_phone') : $post->hide_phone;
  $districtId = old('district', $post->district_id);
  $thanaId = old('thana', $post->thana_id);
  $title = old('title') ?: $post->name;
  $description = old('description') ?: $post->description;
  $statusBadge = [
      'temp' => ['Draft', 'bg-label-secondary'],
      'pending' => ['Pending Review', 'bg-label-warning'],
      'active' => ['Live', 'bg-label-success'],
      'solded' => ['Sold', 'bg-label-info'],
      'cancelled' => ['Rejected', 'bg-label-danger'],
  ][$post->status] ?? [ucfirst($post->status), 'bg-label-secondary'];
@endphp

<form action="{{route('seller.listingsAction',['update',$post->id])}}" method="post" class="pa-form">
  @csrf

  {{-- Page Header --}}
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
      <h4 class="mb-1 fw-bold text-dark">{{$isNew ? 'Post New Ad' : 'Edit Ad'}}</h4>
      <p class="text-muted mb-0 small">{{$isNew ? 'Publish a product ad with clear photos, price, and category details.' : 'Update the details and photos of your ad listing.'}}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge {{$statusBadge[1]}} px-3 py-2">{{$statusBadge[0]}}</span>
      <a href="{{route('seller.listings')}}" class="btn btn-outline-secondary btn-sm">Discard</a>
      <button class="btn btn-primary btn-sm" type="submit">
        <i class="bx bx-check me-1"></i> {{$isNew ? 'Publish Ad' : ($post->status=='cancelled' ? 'Resubmit' : 'Save Changes')}}
      </button>
    </div>
  </div>

  @if($post->status=='cancelled')
  <div class="alert alert-danger d-flex gap-2 align-items-start mb-4" role="alert">
    <i class="bx bx-error-circle fs-4"></i>
    <div>
      <div class="fw-bold">This ad was rejected by the admin.</div>
      <div>{{$post->reject_reason ?: 'Please review the ad details and guidelines before resubmitting.'}}</div>
      <div class="small mt-1">Make necessary fixes and click "Resubmit" to send it back for review.</div>
    </div>
  </div>
  @endif

  <div class="row g-4">
    {{-- ==================== LEFT COLUMN (8 Cols): Product Information, Pricing, Location, Contact ==================== --}}
    <div class="col-xl-8 col-lg-8 col-12">
      
      <!-- Card 1: Product Information -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-info-circle"></i> Product Information</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label mb-0" for="aeTitle">Ad Title <span class="text-danger">*</span></label>
              <small class="text-muted"><span data-count-for="aeTitle">{{mb_strlen((string) $title)}}</span>/191</small>
            </div>
            <input type="text" class="form-control" id="aeTitle" placeholder="e.g. Fresh Himsagar Mango, 20 kg crate" value="{{$title}}" name="title" maxlength="191" required>
            @if ($errors->has('title'))<div class="invalid-feedback d-block">{{ $errors->first('title') }}</div>@endif
          </div>

          <div>
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label mb-0" for="aeDescription">Product Description <span class="text-danger">*</span></label>
              <small class="text-muted"><span data-count-for="aeDescription">{{mb_strlen((string) $description)}}</span>/5000</small>
            </div>
            <textarea class="form-control" id="aeDescription" rows="8" placeholder="Describe the quality, quantity, packaging, condition, harvesting, delivery options..." name="description" maxlength="5000" required>{{$description}}</textarea>
            @if ($errors->has('description'))<div class="invalid-feedback d-block">{{ $errors->first('description') }}</div>@endif
          </div>
        </div>
      </div>

      <!-- Card 2: Pricing & Negotiation -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-dollar-circle"></i> Pricing</h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label class="form-label" for="aePrice">Price (BDT) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">৳ BDT</span>
                <input type="number" class="form-control" id="aePrice" placeholder="0.00" min="0" step="any" inputmode="decimal" value="{{old('price')?:$post->price}}" name="price" required>
              </div>
              @if ($errors->has('price'))<div class="invalid-feedback d-block">{{ $errors->first('price') }}</div>@endif
            </div>
            <div class="col-md-6 col-12 d-flex align-items-center">
              <div class="form-check form-switch pt-md-4">
                <input class="form-check-input" type="checkbox" id="aeNegotiable" name="negotiable" {{$negotiable?'checked':''}}>
                <label class="form-check-label fw-medium text-dark ms-2" for="aeNegotiable">Price is negotiable</label>
                <div class="form-text mt-0">Allows interested buyers to discuss price.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3: Location -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-map-pin"></i> Location</h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label class="form-label" for="district">District <span class="text-danger">*</span></label>
              <select id="district" class="form-select" name="district" data-geo-target="#thana" required>
                <option value="">Select district</option>
                @foreach(geoData(3) as $district)
                <option value="{{$district->id}}" {{$districtId == $district->id?'selected':''}}>{{$district->name}}</option>
                @endforeach
              </select>
              @if ($errors->has('district'))<div class="invalid-feedback d-block">{{ $errors->first('district') }}</div>@endif
            </div>

            <div class="col-md-6 col-12">
              <label class="form-label" for="thana">Thana / Upazila <span class="text-danger">*</span></label>
              <select id="thana" class="form-select" name="thana" required>
                <option value="">Select thana</option>
                @if($districtId)
                @foreach(geoData(4,$districtId) as $thana)
                <option value="{{$thana->id}}" {{$thanaId == $thana->id?'selected':''}}>{{$thana->name}}</option>
                @endforeach
                @endif
              </select>
              @if ($errors->has('thana'))<div class="invalid-feedback d-block">{{ $errors->first('thana') }}</div>@endif
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4: Contact Information -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-phone-call"></i> Contact Details</h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6 col-12">
              <label class="form-label" for="aeMobile">Contact Phone Number <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                <input type="tel" class="form-control" id="aeMobile" placeholder="01XXXXXXXXX" inputmode="tel" value="{{old('mobile')?:($post->phone_number?:Auth::user()->mobile)}}" name="mobile" maxlength="20" required>
              </div>
              @if ($errors->has('mobile'))<div class="invalid-feedback d-block">{{ $errors->first('mobile') }}</div>@endif
            </div>
            <div class="col-md-6 col-12 d-flex align-items-center">
              <div class="form-check form-switch pt-md-4">
                <input class="form-check-input" type="checkbox" id="aeHidePhone" name="hide_phone" {{$hidePhone?'checked':''}}>
                <label class="form-check-label fw-medium text-dark ms-2" for="aeHidePhone">Hide phone number</label>
                <div class="form-text mt-0">Buyers will contact you solely via messages.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- ==================== RIGHT COLUMN (4 Cols): Categories & Product Photos (Separated as requested) ==================== --}}
    <div class="col-xl-4 col-lg-4 col-12">

      <!-- Card 1: Categories (Right Column) -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-category"></i> Category</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label" for="aeCategory">Primary Category <span class="text-danger">*</span></label>
            <select class="form-select" id="aeCategory" name="category" required>
              <option value="">Select a category</option>
              @foreach($categories as $ctg)
              <option value="{{$ctg->id}}" {{(old('category')?:$post->category_id)==$ctg->id?'selected':''}}>{{$ctg->name}}</option>
              @endforeach
            </select>
            @if ($errors->has('category'))<div class="invalid-feedback d-block">{{ $errors->first('category') }}</div>@endif
          </div>

          <div id="subCategoryBox" class="mb-2 pa-sub-hidden">
            <label class="form-label d-block">Sub Categories <span class="text-muted small">(optional)</span></label>
            <div class="pa-subcats">
              @foreach($categories as $ctg)
              @if($subCategories->has($ctg->id))
              <div class="sub-group pa-sub-hidden" data-parent="{{$ctg->id}}">
                @foreach($subCategories[$ctg->id] as $sub)
                <input class="btn-check" type="checkbox" name="subcategories[]" value="{{$sub->id}}" id="sub{{$sub->id}}" autocomplete="off" {{in_array($sub->id,$checkedSubs)?'checked':''}} disabled>
                <label for="sub{{$sub->id}}">{{$sub->name}}</label>
                @endforeach
              </div>
              @endif
              @endforeach
            </div>
            @if ($errors->has('subcategories') || $errors->has('subcategories.*'))<div class="invalid-feedback d-block">{{ $errors->first('subcategories') ?: $errors->first('subcategories.*') }}</div>@endif
          </div>
        </div>
      </div>

      <!-- Card 2: Product Photos (Right Column) -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-images"></i> Product Photos</h5>
        </div>
        <div class="card-body">
          <p class="text-muted small mb-3">Upload clear, well-lit photos. The main photo will be featured across the marketplace.</p>
          <div class="uploadImages">
            @include(sellerTheme().'uploadImages')
          </div>
        </div>
      </div>

      <!-- Card 3: Publishing Actions (Right Column) -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0"><i class="bx bx-send"></i> Publish Listing</h5>
        </div>
        <div class="card-body">
          <div class="alert alert-light border small text-muted mb-3">
            <i class="bx bx-shield-quarter text-primary me-1"></i>
            {{$isNew || $post->status=='cancelled' ? 'Your ad will be reviewed by admin before becoming live on the marketplace.' : 'Changes will be saved and updated immediately.'}}
          </div>
          <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">
              <i class="bx bx-check-circle me-1"></i> {{$isNew ? 'Publish Ad Now' : ($post->status=='cancelled' ? 'Resubmit for Review' : 'Save Changes')}}
            </button>
            <a href="{{route('seller.listings')}}" class="btn btn-outline-secondary">
              Cancel &amp; Return
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</form>
@endsection

@push('js')
<script>
  $(function () {
    // Show only the sub categories of the selected category; hidden ones are disabled so they are not submitted.
    function showSubCategories() {
      var parent = $('select[name="category"]').val(), found = false;
      $('#subCategoryBox .sub-group').each(function () {
        var match = parent && String($(this).data('parent')) === String(parent);
        if (match) {
          $(this).removeClass('pa-sub-hidden').show().find('input').prop('disabled', false);
          found = true;
        } else {
          $(this).addClass('pa-sub-hidden').hide().find('input').prop('disabled', true).prop('checked', false);
        }
      });
      if (found) {
        $('#subCategoryBox').removeClass('pa-sub-hidden').show();
      } else {
        $('#subCategoryBox').addClass('pa-sub-hidden').hide();
      }
    }
    $('select[name="category"]').on('change', showSubCategories);
    showSubCategories();

    // Live character counters for the title and description.
    $('[data-count-for]').each(function () {
      var counter = $(this);
      $('#' + counter.data('count-for')).on('input', function () { counter.text(this.value.length); });
    });

    // After a failed submit, bring the first error into view.
    var firstError = $('.pa-form .invalid-feedback.d-block').first();
    if (firstError.length) { $('html, body').animate({scrollTop: firstError.closest('.card').offset().top - 20}, 200); }

    // Sends a photo request and redraws the photo box with the answer (no loading overlay).
    function refresh(request) {
      var box = $('.uploadImages');
      return $.ajax(request).done(function (response) {
        if (response.success) { box.html(response.view); } else { alert('Upload failed!'); }
      }).fail(function (xhr) {
        alert(xhr.status == 422 && xhr.responseJSON ? 'Upload failed: ' + xhr.responseJSON.message : 'Upload failed!');
      });
    }

    // Shrink photo before upload
    function shrinkPhoto(file) {
      return new Promise(function (resolve) {
        if (!/^image\//.test(file.type) && !/\.(heic|heif)$/i.test(file.name)) { resolve(file); return; }
        var url = URL.createObjectURL(file);
        var img = new Image();
        img.onload = function () {
          var max = 1280, scale = Math.min(1, max / Math.max(img.naturalWidth, img.naturalHeight));
          var canvas = document.createElement('canvas');
          canvas.width = Math.max(1, Math.round(img.naturalWidth * scale));
          canvas.height = Math.max(1, Math.round(img.naturalHeight * scale));
          var ctx = canvas.getContext('2d');
          ctx.fillStyle = '#fff';
          ctx.fillRect(0, 0, canvas.width, canvas.height);
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
          URL.revokeObjectURL(url);
          canvas.toBlob(function (blob) {
            var name = file.name.replace(/\.[^.]+$/, '') + '.jpg';
            resolve(blob ? new File([blob], name, {type: 'image/jpeg'}) : file);
          }, 'image/jpeg', 0.92);
        };
        img.onerror = function () { URL.revokeObjectURL(url); resolve(file); };
        img.src = url;
      });
    }

    $(document).on('change', '.uploadImageFile', function () {
      var input = this, url = $(input).data('url'), main = $(input).data('main') == 1;
      var files = Array.prototype.slice.call(input.files, 0, main ? 1 : 9);
      if (!files.length) { return; }

      function send(i) {
        if (i >= files.length) { return; }
        shrinkPhoto(files[i]).then(function (photo) {
          var formData = new FormData();
          formData.append('images[]', photo);
          formData.append('main', main ? 1 : 0);
          refresh({url: url, type: 'POST', data: formData, contentType: false, processData: false})
            .done(function () { send(i + 1); })
            .fail(function (xhr) {
              var full = xhr.responseJSON && /at most/.test(xhr.responseJSON.message || '');
              if (!full) { send(i + 1); }
            });
        });
      }
      send(0);
    });

    $(document).on('click', '.js-ph-main', function () {
      refresh({url: $(this).data('url'), type: 'GET'});
    });

    $(document).on('click', '.js-ph-del', function () {
      if (confirm('Do you want to delete this photo?')) {
        refresh({url: $(this).data('url'), type: 'GET'});
      }
    });
  });
</script>
@endpush

