{{-- One community post. Needs $role and $post (with user, likes_count, comments_count, liked). $full shows the whole text. --}}
@php
  $full = $full ?? false;
  $author = $post->user;
  $viewUrl = route($role.'.communityAction',['view',$post->id]);
@endphp
<div class="av-post-card">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2 av-post-author">
      <img src="{{$author?->avatar(80)}}" alt="{{$author?->name}}">
      <div>
        <div class="fw-semibold" style="font-size:.85rem;">{{$author?->name ?: 'Deleted user'}}</div>
        <div class="text-muted" style="font-size:.72rem;">{{$post->created_at?->shortRelativeDiffForHumans()}}</div>
      </div>
    </div>
    <a href="{{route($role.'.community',['tag'=>$post->tag])}}" class="av-post-tag text-decoration-none">{{$post->tag}}</a>
  </div>
  <div class="fw-bold mt-2" style="font-size:.92rem;">
    @if($full){{$post->title}}@else<a href="{{$viewUrl}}" class="text-reset text-decoration-none">{{$post->title}}</a>@endif
  </div>
  <div class="text-secondary" style="font-size:.85rem;">
    @if($full){!! nl2br(e($post->body)) !!}@else{{Str::limit($post->body,220)}}@endif
  </div>
  <div class="d-flex gap-3 mt-2 align-items-center">
    <form action="{{route($role.'.communityAction',['like',$post->id])}}" method="POST" class="d-inline">
      @csrf
      <button type="submit" class="av-post-stat {{$post->liked?'liked':''}}" title="{{$post->liked?'Unlike':'Like'}}"><i class="fa-{{$post->liked?'solid':'regular'}} fa-thumbs-up me-1"></i>{{number_format($post->likes_count)}}</button>
    </form>
    <a href="{{$viewUrl}}#comments" class="av-post-stat text-decoration-none"><i class="fa-regular fa-comment me-1"></i>{{number_format($post->comments_count)}} {{$post->comments_count==1?'comment':'comments'}}</a>
    @if($role != 'expert')
    <button type="button" class="av-post-stat" data-share="{{$viewUrl}}" data-share-title="{{$post->title}}"><i class="fa-regular fa-share-from-square me-1"></i><span>Share</span></button>
    @endif
  </div>
</div>
