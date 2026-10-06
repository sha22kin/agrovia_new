{{-- One community post with its comments. Needs $role, $post and $comments. --}}
@php $me = Auth::id(); @endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">Community</h5>
  <a href="{{route($role.'.community')}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to Community</a>
</div>

<div class="av-card mt-0">
  @include('dashboard.partials.community-card',['full'=>true])
  @if($post->user_id==$me)
  <form action="{{route($role.'.communityAction',['delete',$post->id])}}" method="POST" class="mt-2" data-confirm="Delete this post and its comments?" data-confirm-ok="Yes, delete" data-confirm-danger>
    @csrf
    <button type="submit" class="btn-av-outline"><i class="fa-regular fa-trash-can me-1"></i> Delete Post</button>
  </form>
  @endif
</div>

<div class="av-card" id="comments">
  <div class="av-card-title"><i class="fa-regular fa-comment"></i> Comments ({{$comments->count()}})</div>
  @forelse($comments as $comment)
  <div class="av-post-card">
    <div class="d-flex justify-content-between align-items-start gap-2">
      <div class="d-flex align-items-center gap-2 av-post-author">
        <img src="{{$comment->user?->avatar(80)}}" alt="{{$comment->user?->name}}">
        <div>
          <div class="fw-semibold" style="font-size:.85rem;">{{$comment->user?->name ?: 'Deleted user'}}</div>
          <div class="text-muted" style="font-size:.72rem;">{{$comment->created_at?->shortRelativeDiffForHumans()}}</div>
        </div>
      </div>
      @if($comment->user_id==$me)
      <form action="{{route($role.'.communityAction',['delete-comment',$post->id])}}" method="POST" data-confirm="Delete your comment?" data-confirm-ok="Yes, delete" data-confirm-danger>
        @csrf
        <input type="hidden" name="comment" value="{{$comment->id}}">
        <button type="submit" class="av-post-stat" title="Delete comment"><i class="fa-regular fa-trash-can"></i></button>
      </form>
      @endif
    </div>
    <div class="text-secondary mt-1" style="font-size:.85rem;">{!! nl2br(e($comment->body)) !!}</div>
  </div>
  @empty
  <div class="av-empty"><i class="fa-regular fa-comment"></i>No comments yet. Be the first to reply.</div>
  @endforelse

  <form method="POST" action="{{route($role.'.communityAction',['comment',$post->id])}}" class="mt-3">
    @csrf
    <div class="d-flex gap-2">
      <input type="text" class="form-control @error('body') is-invalid @enderror" name="body" value="{{old('body')}}" maxlength="1000" placeholder="Write a comment..." autocomplete="off" required>
      <button type="submit" class="btn-av-primary" title="Post comment"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
    @error('body')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
  </form>
</div>
@include('dashboard.partials.community-share')
