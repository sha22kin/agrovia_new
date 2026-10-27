{{-- Inbox and chat shared by the buyer and seller themes. Needs $role, $conversations, $selected, $draft, $peer, $thread. --}}
@php
  $me = Auth::id();
  $peerKey = $role == 'buyer' ? 'seller' : 'buyer';
@endphp
<h5 class="fw-bold mb-3">Messages</h5>
<div class="av-card mt-0 p-2 p-md-3">
  <div class="row g-0">
    <div class="col-12 col-md-4 border-end" style="max-height:520px;overflow-y:auto;">
      <div class="p-2">
        <input type="text" id="msgSearch" class="form-control mb-2" placeholder="Search messages...">

        @forelse($conversations as $conversation)
        @php
          $other = $conversation->{$peerKey};
          $last = $conversation->lastMessage;
        @endphp
        <a href="{{route($role.'.messages',['c'=>$conversation->id])}}" class="av-msg-item {{$conversation->unread_count?'unread':''}} {{$selected && $selected->id==$conversation->id?'active':''}} text-decoration-none text-reset" data-search="{{strtolower(($other?->name ?: '').' '.($last?->body ?: ''))}}">
          <img src="{{$other?->avatar(80)}}" alt="{{$other?->name}}">
          <div class="flex-grow-1 overflow-hidden">
            <div class="av-msg-name">{{$other?->name ?: 'Deleted user'}}</div>
            <div class="av-msg-preview">{{$last ? ($last->sender_id==$me?'You: ':'').Str::limit(strip_tags($last->body),40) : ''}}</div>
          </div>
          <div class="text-end">
            <div class="av-msg-time">{{$conversation->last_message_at?->shortAbsoluteDiffForHumans()}}</div>
            @if($conversation->unread_count)<span class="av-msg-badge">{{$conversation->unread_count}}</span>@endif
          </div>
        </a>
        @empty
        <div class="av-empty py-4"><i class="fa-regular fa-message"></i>
          @if($role=='buyer')No messages yet. Open an ad and tap "Chat" to talk to the seller.@elseif($role=='expert')No messages yet. Messages from farmers will appear here.@else No messages yet. Buyers' messages will appear here.@endif
        </div>
        @endforelse
      </div>
    </div>

    <div class="col-12 col-md-8 d-flex flex-column" style="min-height:420px;">
      @if($peer)
      <div class="d-flex align-items-center gap-2 p-3 border-bottom">
        <img src="{{$peer->avatar(80)}}" alt="{{$peer->name}}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
        <div>
          <div class="fw-semibold" style="font-size:.87rem;">{{$peer->name}}</div>
          {{-- An expert sees just the name of the farmer; a buyer / seller sees what the other one is. --}}
          @if($role != 'expert')
          <div class="text-muted" style="font-size:.72rem;">{{$peer->isExpert() ? 'Expert' : ucfirst($peerKey)}}@if($peer->isExpert() && $peer->expertProfile?->headline) · {{$peer->expertProfile->headline}}@elseif($peer->company_name) · {{$peer->company_name}}@endif</div>
          @endif
        </div>
      </div>
      <div class="flex-grow-1 p-3 av-thread" id="msgThread">
        @forelse($thread as $message)
        <div class="av-thread-bubble {{$message->sender_id==$me?'me':'them'}}">
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
        @empty
        <div class="av-empty"><i class="fa-regular fa-message"></i>Say hello to {{$peer->name}}.</div>
        @endforelse
      </div>
      <form method="POST" action="{{route($role.'.messageSend')}}" class="p-3 border-top">
        @csrf
        @if($selected)<input type="hidden" name="conversation" value="{{$selected->id}}">@else<input type="hidden" name="to" value="{{$peer->id}}">@endif
        @if($aboutAd)
        <input type="hidden" name="ad" value="{{$aboutAd->id}}">
        <div class="av-msg-reply">
          <a href="{{route('serviceView',$aboutAd->slug ?: 'no-title')}}" target="_blank" class="av-msg-ad">
            <img src="{{asset($aboutAd->fiImage())}}" alt="{{$aboutAd->name}}">
            <div class="overflow-hidden">
              <small>Replying about this ad</small>
              <b>{{$aboutAd->name}}</b>
              <span>৳{{number_format($aboutAd->price)}}</span>
            </div>
          </a>
          <a href="{{request()->fullUrlWithoutQuery('ad')}}" class="av-msg-reply-close" title="Don't attach this ad">&times;</a>
        </div>
        @endif
        <div class="d-flex gap-2">
          <input type="text" class="form-control @error('body') is-invalid @enderror" name="body" value="{{old('body')}}" maxlength="2000" placeholder="Type a message..." autocomplete="off" required>
          <button type="submit" class="btn-av-primary" title="Send"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
        @error('body')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
      </form>
      @else
      <div class="av-empty my-auto"><i class="fa-regular fa-comments"></i>Select a conversation to read it.</div>
      @endif
    </div>
  </div>
</div>

@push('js')
<script>
  (function () {
    var thread = document.getElementById('msgThread');
    if (thread) { thread.scrollTop = thread.scrollHeight; }
    var search = document.getElementById('msgSearch');
    if (search) {
      search.addEventListener('input', function () {
        var q = search.value.toLowerCase();
        document.querySelectorAll('.av-msg-item').forEach(function (el) {
          el.classList.toggle('d-none', (el.dataset.search || '').indexOf(q) === -1);
        });
      });
    }
  })();
</script>
@endpush
