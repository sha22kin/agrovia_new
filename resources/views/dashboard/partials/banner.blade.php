{{-- Profile banner. Needs $user and $summary. --}}
@php $role = $summary['role']; @endphp
<div class="av-banner">
  <div class="row align-items-center g-3">
    <div class="col-12 col-md-8">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="av-avatar-wrap">
          <img src="{{$user->imageFile?route('imageView2',['profile',$user->imageName(),'w'=>200,'h'=>200]):asset($user->image())}}" alt="{{$user->name}}">
        </div>
        <div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <h4 class="mb-0 fw-bold">{{$user->name}}</h4>
            <span class="av-verified-badge">
              @if($summary['verified'])<i class="fa-solid fa-circle-check"></i> Verified {{ucfirst($role)}}@else{{ucfirst($role)}}@endif
            </span>
          </div>
          <div class="meta-line mt-2"><i class="fa-regular fa-id-card"></i> {{ucfirst($role)}} ID: {{$summary['publicId']}} <i class="fa-regular fa-copy ms-1" style="cursor:pointer;" data-copy="{{$summary['publicId']}}" title="Copy"></i></div>
          <div class="meta-line mt-1"><i class="fa-solid fa-location-dot"></i> {{$summary['location']}}</div>
          @if($user->created_at)
          <div class="meta-line mt-1"><i class="fa-regular fa-calendar"></i> Member since {{$user->created_at->format('M Y')}}</div>
          @endif
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4 text-md-end">
      <a href="{{route($role.'.profile')}}" class="btn btn-edit-profile"><i class="fa-solid fa-pen me-2"></i>Edit Profile</a>
    </div>
  </div>
</div>
