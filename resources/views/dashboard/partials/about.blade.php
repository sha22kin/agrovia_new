{{-- About card. Needs $user and $summary. --}}
<div class="av-card">
  <div class="av-card-title"><i class="fa-regular fa-address-card"></i> About Me</div>
  @if($user->profile)
  <p class="mb-0 mt-2 text-secondary" style="font-size:.87rem;">{{$user->profile}}</p>
  @else
  <p class="mb-0 mt-2 text-muted" style="font-size:.87rem;">Tell {{$summary['role']=='seller'?'buyers':'sellers'}} a little about you. <a href="{{route($summary['role'].'.profile')}}" class="av-link-more">Add a short bio</a></p>
  @endif
  <div class="av-info-strip">
    <span><i class="fa-solid fa-phone"></i>{{$user->mobile ?: 'No phone added'}}</span>
    <span><i class="fa-regular fa-envelope"></i>{{$user->email ?: 'No email added'}}</span>
    @if($user->created_at)<span><i class="fa-solid fa-user-plus"></i>Joined: {{$user->created_at->format('M d, Y')}}</span>@endif
  </div>
</div>
