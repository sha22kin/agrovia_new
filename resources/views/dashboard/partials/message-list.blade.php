{{-- Conversation list of the inbox. Needs $role, $conversations, $selected and $me. --}}
@php $peerKey = $role == 'buyer' ? 'seller' : 'buyer'; @endphp
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
