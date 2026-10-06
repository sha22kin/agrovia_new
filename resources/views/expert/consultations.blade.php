@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Consultations')}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">Consultations</h5>
</div>
<div class="d-flex gap-2 flex-wrap mb-3 mt-2">
  @foreach(['upcoming'=>'Upcoming','completed'=>'Completed','cancelled'=>'Cancelled'] as $key=>$label)
  <a href="{{route('expert.consultations',$key=='upcoming'?[]:['status'=>$key])}}" class="av-cat-pill {{$status==$key?'active':''}}">{{$label}} ({{number_format($counts[$key])}})</a>
  @endforeach
</div>

<div class="av-card mt-0">
  @forelse($consultations as $consultation)
  @php
    $farmer = $consultation->farmer;
    $passed = $consultation->scheduled_at && $consultation->scheduled_at->isPast();
    $pending = $consultation->status == 'pending';
    $confirmed = $consultation->status == 'confirmed';
  @endphp
  <div class="av-consult-row {{$loop->last?'':'mb-2'}}">
    <img class="av-consult-avatar" src="{{$farmer?->avatar(80)}}" alt="{{$farmer?->name}}">
    <div class="flex-grow-1 overflow-hidden">
      <div class="fw-semibold" style="font-size:.87rem;">
        {{$farmer?->name ?: 'Farmer'}} <span class="text-muted fw-normal">&middot; {{$consultation->topic}}</span>
        @if($pending)<span class="badge-soft-amber ms-1">Waiting for you</span>@elseif($confirmed && $passed)<span class="badge-soft-amber ms-1">Time passed</span>@endif
      </div>
      <div class="text-muted" style="font-size:.76rem;">
        {{$consultation->modeLabel()}} &middot; {{$consultation->whenLabel()}}
        @if($consultation->status == 'completed')
        @if($consultation->fee > 0) &middot; {{$currency}} {{number_format($consultation->fee)}}@endif
        @if($consultation->rating) &middot; <span class="av-stars">{{str_repeat('★',$consultation->rating)}}</span>@endif
        @endif
      </div>
      @if($consultation->status == 'completed' && $consultation->review)
      <div class="text-secondary mt-1" style="font-size:.76rem;">"{{Str::limit($consultation->review,120)}}"</div>
      @endif
    </div>

    @if($pending)
    <form action="{{route('expert.consultationAction',[$consultation->id,'accept'])}}" method="POST">@csrf<button type="submit" class="btn-av-primary btn-sm">Accept</button></form>
    @endif

    @if($confirmed && $consultation->meeting_link)
    <a href="{{$consultation->meeting_link}}" target="_blank" rel="noopener" class="btn-av-outline btn-sm py-1 px-2">Join</a>
    @elseif($confirmed && $consultation->mode == 'phone' && $farmer?->mobile)
    <a href="tel:{{$farmer->mobile}}" class="btn-av-outline btn-sm py-1 px-2"><i class="fa-solid fa-phone me-1"></i>Call</a>
    @endif

    @if($confirmed && $passed)
    <form action="{{route('expert.consultationAction',[$consultation->id,'complete'])}}" method="POST">@csrf<button type="submit" class="btn-av-primary btn-sm">Mark Completed</button></form>
    @endif

    @if($pending || $confirmed)
    <button type="button" class="btn-av-outline btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#rescheduleModal"
      data-id="{{$consultation->id}}" data-topic="{{$consultation->topic}}" data-when="{{$consultation->scheduled_at?->format('Y-m-d\TH:i')}}">Reschedule</button>
    @endif

    @if($pending)
    <form action="{{route('expert.consultationAction',[$consultation->id,'decline'])}}" method="POST" data-confirm="Decline the request of {{$farmer?->name ?: 'this farmer'}}?" data-confirm-ok="Yes, decline" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Decline"><i class="fa-solid fa-xmark"></i></button></form>
    @elseif($confirmed && !$passed)
    <form action="{{route('expert.consultationAction',[$consultation->id,'cancel'])}}" method="POST" data-confirm="Cancel the consultation with {{$farmer?->name ?: 'this farmer'}}?" data-confirm-ok="Yes, cancel" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Cancel"><i class="fa-solid fa-ban"></i></button></form>
    @endif
  </div>
  @empty
  <div class="av-empty"><i class="fa-solid fa-calendar-check"></i>
    @if($status == 'upcoming')No upcoming consultations. Requests and confirmed sessions of farmers show up here.
    @elseif($status == 'completed')No completed consultations yet.
    @else No cancelled consultations.@endif
  </div>
  @endforelse

  {{$consultations->links('dashboard.partials.pagination')}}
</div>

@include(expertTheme().'partials.reschedule-modal')
@endsection
