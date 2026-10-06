{{-- Verification status + business type + areas. Needs $summary. --}}
<div class="av-mini-card">
  <div class="av-card-title"><i class="fa-solid fa-shield-halved"></i> Verification Status</div>
  @foreach($summary['verification'] as [$label,$ok,$notText])
  <div class="verify-item {{$ok?'':'off'}}"><i class="fa-solid {{$ok?'fa-circle-check':'fa-circle-xmark'}}"></i> {{$ok?$label:$notText}}</div>
  @endforeach
  <a href="{{route($summary['role'].'.profile')}}" class="btn btn-verify-details d-block text-center">Complete Your Profile</a>
</div>

<div class="av-mini-card">
  <div class="av-card-title"><i class="fa-solid fa-people-group"></i> Business Type</div>
  <div class="mt-2"><span class="badge-soft-green">{{$summary['businessType']}}</span></div>
  <div class="av-card-title mt-3"><i class="fa-solid fa-map-location-dot"></i> Areas of Operation</div>
  <div class="area-tag text-secondary mt-1">{{$summary['areas'] ?: 'Not set yet'}}</div>
</div>
