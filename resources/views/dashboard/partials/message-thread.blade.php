{{-- Messages of the open chat with the pinned ones on top. Needs $thread and $me (the viewer's id). --}}
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

@forelse($thread as $message)
@include('dashboard.partials.message-bubble')
@empty
<div class="av-empty" id="msgEmpty"><i class="fa-regular fa-message"></i>No messages yet. Say hello!</div>
@endforelse
