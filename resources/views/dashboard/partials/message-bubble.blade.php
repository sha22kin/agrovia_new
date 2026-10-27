{{-- One chat message. Needs $message and $me (the viewer's id). --}}
<div class="av-thread-bubble {{$message->sender_id==$me?'me':'them'}}" data-id="{{$message->id}}">
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
  {!! nl2br(e($message->body)) !!}
  <div class="av-thread-time">{{$message->created_at?->format('M d, h:i A')}}</div>
</div>
