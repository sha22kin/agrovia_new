{{-- Community feed. Needs $role, $posts and $tag. --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">Community</h5>
  <a href="{{route($role.'.communityAction','create')}}" class="btn-av-primary"><i class="fa-solid fa-plus me-2"></i>New Post</a>
</div>
<p class="text-secondary" style="font-size:.85rem;">{{$role == 'expert' ? 'Share expert insight and answer discussions from farmers and buyers.' : 'Discuss prices, farming tips and trends with other buyers and sellers.'}}</p>

@if($tag)
<div class="mb-2 d-flex align-items-center gap-2" style="font-size:.82rem;">
  <span class="text-muted">Showing</span> <span class="av-post-tag">{{$tag}}</span>
  <a href="{{route($role.'.community')}}" class="av-link-more">Show all posts</a>
</div>
@endif

<div class="av-card mt-0">
  @forelse($posts as $post)
  @include('dashboard.partials.community-card')
  @empty
  <div class="av-empty"><i class="fa-regular fa-comments"></i>No posts yet. <a href="{{route($role.'.communityAction','create')}}">Start the first discussion</a></div>
  @endforelse

  {{$posts->links('dashboard.partials.pagination')}}
</div>
@include('dashboard.partials.community-share')
