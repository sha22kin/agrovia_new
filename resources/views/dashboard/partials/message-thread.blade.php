{{-- Messages of the open chat with the pinned ones on top and a label for every day. Needs $thread and $me (the viewer's id). --}}
@php $pinned = $thread->filter(fn ($m) => $m->pinned_at && !$m->isDeleted())->sortByDesc('pinned_at'); @endphp
@if($pinned->isNotEmpty())
<div class="av-msg-pinned">
  <i class="fa-solid fa-thumbtack"></i>
  <a href="#msg-{{$pinned->first()->id}}" class="flex-grow-1 text-reset text-decoration-none overflow-hidden" data-jump="{{$pinned->first()->id}}">
    <small>Pinned message{{$pinned->count() > 1 ? ' · '.$pinned->count().' pinned' : ''}}</small>
    <span>{{$pinned->first()->preview(80)}}</span>
  </a>
</div>
@endif

@php $day = null; @endphp
@forelse($thread as $message)
  @php $msgDay = $message->created_at?->toDateString(); @endphp
  @if($msgDay !== $day)
    @php $day = $msgDay; @endphp
    <div class="av-msg-day"><span>{{$message->created_at?->isToday() ? 'Today' : ($message->created_at?->isYesterday() ? 'Yesterday' : $message->created_at?->format('d M Y'))}}</span></div>
  @endif
  @include('dashboard.partials.message-bubble')
@empty
<div class="av-empty h-100" id="msgEmpty"><i class="fa-regular fa-hand"></i>No messages yet. Say hello!</div>
@endforelse
