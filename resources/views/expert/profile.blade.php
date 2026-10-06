@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('My Profile')}}</title>
@endsection

@section('contents')
<h1 class="av-page-title">My Profile</h1>
<div class="av-card first mt-2">
  <div class="av-card-title mb-3">Profile</div>
  @include('dashboard.profile-form',['action'=>route('expert.profile')])
</div>

<div class="av-card">
  <div class="av-card-title mb-3"><i class="fa-solid fa-graduation-cap"></i> Expert Details</div>
  <form method="POST" action="{{route('expert.profileDetails')}}">
    @csrf
    <div class="row g-3">
      <div class="col-12">
        <label class="form-label">Title</label>
        <input type="text" class="form-control @error('headline') is-invalid @enderror" name="headline" value="{{old('headline',$profile->headline)}}" maxlength="150" placeholder="e.g. Agronomist · Soil &amp; Crop Health Specialist">
        @error('headline')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-8">
        <label class="form-label">Specializations</label>
        <input type="text" class="form-control @error('specializations') is-invalid @enderror" name="specializations" value="{{old('specializations',$profile->specializations)}}" maxlength="300" placeholder="Soil Health, Crop Disease, Irrigation (separate with commas)">
        @error('specializations')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Consultation fee ({{general()->currency ?: '৳'}})</label>
        <input type="number" step="any" min="0" class="form-control @error('fee') is-invalid @enderror" name="fee" value="{{old('fee',$profile->fee !== null ? (float) $profile->fee : '')}}" placeholder="500">
        @error('fee')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
    </div>
    <div class="mt-3 d-flex align-items-center gap-3 flex-wrap">
      <button type="submit" class="btn btn-theme">Save Expert Details</button>
      <span class="text-muted" style="font-size:.8rem;">@if($profile->isVerified())<i class="fa-solid fa-circle-check text-success me-1"></i>Verified expert @else Your profile is waiting for the admin's verification @endif</span>
    </div>
  </form>
</div>
@endsection
