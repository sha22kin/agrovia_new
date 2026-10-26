{{-- Profile form shared by the buyer and seller dashboards. Needs $user and $action. --}}
<form method="POST" action="{{$action}}" enctype="multipart/form-data">
  @csrf
  <div class="row align-items-center mb-4" id="profilePhoto">
    <div class="col-auto">
      <img src="{{$user->imageFile?route('imageView2',['profile',$user->imageName(),'w'=>120,'h'=>120]):asset($user->image())}}" alt="{{$user->name}}" class="profile-photo">
    </div>
    <div class="col">
      <label class="form-label">Profile photo</label>
      <div>
        <button type="button" class="btn btn-theme btn-sm" id="photoPickBtn"><i class="fa-solid fa-camera me-1"></i> Change photo</button>
        <input type="file" id="photoInput" class="d-none" accept="image/jpeg,image/png,image/gif,image/webp">
      </div>
      <div class="form-text">JPG, PNG or WebP. You crop it after choosing; it is saved as a square WebP.</div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Name <span class="text-danger">*</span></label>
      <input required class="form-control" value="{{old('name')?:$user->name}}" name="name" type="text" placeholder="Enter your name">
      @if ($errors->has('name'))<div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>@endif
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Gender <span class="text-danger">*</span></label>
      @php $genderValue = old('gender') ?: $user->gender; @endphp
      <select required class="form-control" name="gender">
        <option value="" disabled {{$genderValue?'':'selected'}}>Select your gender</option>
        @foreach(['Male','Female','Other'] as $genderOption)
        <option value="{{$genderOption}}" {{$genderValue==$genderOption?'selected':''}}>{{$genderOption}}</option>
        @endforeach
      </select>
      @if ($errors->has('gender'))<div class="invalid-feedback d-block">{{ $errors->first('gender') }}</div>@endif
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Email <span class="text-danger">*</span></label>
      <input required class="form-control" value="{{old('email')?:$user->email}}" name="email" type="email" placeholder="Enter your email">
      @if ($errors->has('email'))<div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>@endif
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Mobile</label>
      <input class="form-control" value="{{old('mobile')?:$user->mobile}}" name="mobile" type="text" placeholder="Enter your mobile">
      @if ($errors->has('mobile'))<div class="invalid-feedback d-block">{{ $errors->first('mobile') }}</div>@endif
    </div>
  </div>

  <h6 class="fw-bold mt-3 mb-3" id="business"><i class="fa-solid fa-briefcase me-2 text-success"></i>Business info</h6>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Company / Business name</label>
      <input class="form-control" value="{{old('company_name')?:$user->company_name}}" name="company_name" type="text" placeholder="e.g. Green Field Agro">
      @if ($errors->has('company_name'))<div class="invalid-feedback d-block">{{ $errors->first('company_name') }}</div>@endif
    </div>
    @if($user->isSeller())
    @php $farm = \App\Models\BusinessProfile::where('user_id',$user->id)->first(); @endphp
    <div class="col-md-6 mb-3">
      <label class="form-label">Total Land Area</label>
      <div class="input-group">
        <input class="form-control" type="number" step="any" min="0" name="land_area" value="{{old('land_area', $farm && $farm->land_area !== null ? (float) $farm->land_area : '')}}" placeholder="e.g. 12">
        <select class="form-select" name="land_unit" style="max-width:110px;" aria-label="Land unit">
          @foreach(\App\Models\BusinessProfile::LAND_UNITS as $unit)
          <option value="{{$unit}}" {{old('land_unit', $farm?->land_unit ?: 'Bigha')==$unit?'selected':''}}>{{$unit}}</option>
          @endforeach
        </select>
      </div>
      @if ($errors->has('land_area'))<div class="invalid-feedback d-block">{{ $errors->first('land_area') }}</div>@endif
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Main Crops or Products</label>
      <input class="form-control" type="text" name="main_crops" maxlength="300" value="{{old('main_crops', $farm?->main_crops)}}" placeholder="e.g. Rice, Potato, Mustard">
      <div class="form-text">Separate with commas.</div>
      @if ($errors->has('main_crops'))<div class="invalid-feedback d-block">{{ $errors->first('main_crops') }}</div>@endif
    </div>
    @endif
    <div class="col-12 mb-3">
      <label class="form-label">About me</label>
      <textarea class="form-control" name="profile" rows="3" maxlength="500" placeholder="A short bio shown on your dashboard">{{old('profile')?:$user->profile}}</textarea>
      @if ($errors->has('profile'))<div class="invalid-feedback d-block">{{ $errors->first('profile') }}</div>@endif
    </div>
  </div>

  <h6 class="fw-bold mt-3 mb-3" id="address"><i class="fa-solid fa-location-dot me-2 text-success"></i>Address</h6>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">District</label>
      <select class="form-select" name="district" id="district" data-geo-target="#city">
        <option value="">Select Option</option>
        @foreach(geoData(3) as $data)
        <option value="{{$data->id}}" {{$user->district==$data->id?'selected':''}}>{{$data->name}}</option>
        @endforeach
      </select>
      @if ($errors->has('district'))<div class="invalid-feedback d-block">{{ $errors->first('district') }}</div>@endif
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">City / Thana</label>
      <select class="form-select" name="city" id="city">
        <option value="">Select Option</option>
        @if($user->district)
        @foreach(geoData(4,$user->district) as $data)
        <option value="{{$data->id}}" {{$user->city==$data->id?'selected':''}}>{{$data->name}}</option>
        @endforeach
        @endif
      </select>
      @if ($errors->has('city'))<div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>@endif
    </div>
    <div class="col-md-8 mb-3">
      <label class="form-label">Address</label>
      <input class="form-control" value="{{old('address')?:$user->address_line1}}" name="address" type="text" placeholder="House, road, area">
      @if ($errors->has('address'))<div class="invalid-feedback d-block">{{ $errors->first('address') }}</div>@endif
    </div>
    <div class="col-md-4 mb-3">
      <label class="form-label">Postal Code</label>
      <input type="text" class="form-control" name="postal_code" value="{{old('postal_code')?:$user->postal_code}}" placeholder="Enter postal code">
      @if ($errors->has('postal_code'))<div class="invalid-feedback d-block">{{ $errors->first('postal_code') }}</div>@endif
    </div>
  </div>
  <button type="submit" class="btn btn-theme">Update Profile</button>
</form>

@include('dashboard.partials.photo-cropper')
