{{-- One chat message with its actions. Needs $message and $me (the viewer's id). --}}
@php
  $mineMsg = $message->sender_id == $me;
  $likeCount = $message->likes->count();
@endphp
<div class="av-msg-row {{$mineMsg?'me':'them'}}" id="msg-{{$message->id}}" data-id="{{$message->id}}">
  <div class="av-thread-bubble {{$mineMsg?'me':'them'}} {{$message->isDeleted()?'deleted':''}}">
    @if($message->isDeleted())
      <i class="fa-solid fa-ban me-1"></i>{{$mineMsg ? 'You deleted this message' : 'This message was deleted'}}
    @else
      @if($message->pinned_at)<div class="av-msg-pinflag"><i class="fa-solid fa-thumbtack"></i> Pinned</div>@endif

      @if($message->replyTo)
      <a href="#msg-{{$message->replyTo->id}}" class="av-msg-quote" data-jump="{{$message->replyTo->id}}">
        <small>{{$message->replyTo->sender_id == $me ? 'You' : ($message->replyTo->sender?->name ?: 'Them')}}</small>
        <span>{{$message->replyTo->preview(70)}}</span>
      </a>
      @endif

      @if($message->post)
      <a href="{{route('serviceView',$message->post->slug ?: 'no-title')}}" target="_blank" class="av-msg-ad">
        <img src="{{asset($message->post->fiImage())}}" alt="{{$message->post->name}}">
        <div class="overflow-hidden">
          <small>About this ad</small>
          <b>{{$message->post->name}}</b>
          <span>৳{{number_format($message->post->price)}}</span>
        </div>
      </a>
      @endif

      <div class="av-msg-text">{!! nl2br(e($message->body)) !!}</div>
    @endif

    <div class="av-thread-time">
      {{$message->created_at?->format('M d, h:i A')}}
      @if($mineMsg && !$message->isDeleted())
        @if($message->read_at)
        <span class="av-msg-status seen" title="Seen {{$message->read_at->format('M d, h:i A')}}"><i class="fa-solid fa-check-double"></i> Seen</span>
        @else
        <span class="av-msg-status" title="Sent"><i class="fa-solid fa-check"></i> Sent</span>
        @endif
      @endif
    </div>

    @if($likeCount)
    <span class="av-msg-likes {{$message->likedBy($me)?'mine':''}}" title="{{$likeCount}} {{Str::plural('like',$likeCount)}}"><i class="fa-solid fa-heart"></i>@if($likeCount > 1) {{$likeCount}}@endif</span>
    @endif
  </div>

  <div class="av-msg-actions">
    @unless($message->isDeleted())
    <button type="button" data-reply="{{$message->id}}" data-preview="{{$message->preview(70)}}" data-who="{{$mineMsg ? 'You' : ($message->sender?->name ?: 'Them')}}" title="Reply"><i class="fa-solid fa-reply"></i></button>
    <button type="button" data-action="like" class="{{$message->likedBy($me)?'active':''}}" title="{{$message->likedBy($me)?'Unlike':'Like'}}"><i class="fa-{{$message->likedBy($me)?'solid':'regular'}} fa-heart"></i></button>
    <button type="button" data-action="pin" class="{{$message->pinned_at?'active':''}}" title="{{$message->pinned_at?'Unpin':'Pin'}}"><i class="fa-solid fa-thumbtack"></i></button>
    @endunless
    <div class="dropdown d-inline-block">
      <button type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Remove"><i class="fa-regular fa-trash-can"></i></button>
      <ul class="dropdown-menu {{$mineMsg?'dropdown-menu-end':''}}">
        <li><button type="button" class="dropdown-item" data-action="delete_me">Delete for me</button></li>
        @if($message->canDeleteForEveryone($me))
        <li><button type="button" class="dropdown-item text-danger" data-action="delete_all">Delete for everyone</button></li>
        @elseif($mineMsg && !$message->isDeleted())
        <li><span class="dropdown-item-text small text-muted">Seen over {{\App\Models\Message::DELETE_FOR_EVERYONE_MINUTES}} min ago:<br>can't delete for everyone</span></li>
        @endif
      </ul>
    </div>
  </div>
</div>
