@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Ask an Expert — Q&A')}}</title>
@endsection

@section('contents')
@php $mine = array_map('mb_strtolower', $specializations); @endphp
<h5 class="fw-bold mb-1">Ask an Expert — Q&amp;A</h5>
<p class="text-secondary" style="font-size:.85rem;">Answer farmer questions in your area of expertise.</p>
<div class="d-flex gap-2 flex-wrap mb-3 mt-2">
  <a href="{{route('expert.qa')}}" class="av-cat-pill {{$status=='unanswered'?'active':''}}">Unanswered ({{number_format($counts['unanswered'])}})</a>
  <a href="{{route('expert.qa',['status'=>'answered'])}}" class="av-cat-pill {{$status=='answered'?'active':''}}">Answered ({{number_format($counts['answered'])}})</a>
</div>

<div class="row">
  <div class="col-lg-8">

    @forelse($items as $item)
    @if($status == 'answered')
    @php $question = $item->question; @endphp
    <div class="av-qa-card">
      <div class="d-flex justify-content-between align-items-start gap-2">
        <span class="av-qa-tag badge-active">Answered</span>
        <button type="button" class="btn-av-outline btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#answerViewModal"
          data-id="{{$item->id}}" data-title="{{$question->title}}" data-body="{{$question->body}}" data-answer="{{$item->body}}">View</button>
      </div>
      <div class="fw-semibold mt-2" style="font-size:.88rem;">{{$question->title}}</div>
      <div class="text-muted mt-1" style="font-size:.76rem;">Asked by {{$question->user?->name ?: 'a farmer'}} &middot; {{$question->created_at?->shortRelativeDiffForHumans()}} &middot; Answered {{$item->created_at?->format('M d, Y')}}</div>
    </div>
    @else
    @php $matches = $item->topic && in_array(mb_strtolower($item->topic), $mine); @endphp
    <div class="av-qa-card">
      <div class="d-flex justify-content-between align-items-start gap-2">
        <span class="av-qa-tag badge-role-expert">{{$item->topic ?: 'General'}}@if($matches) <i class="fa-solid fa-star ms-1" title="Matches your specialization"></i>@endif</span>
        <button type="button" class="btn-av-primary btn-sm" data-bs-toggle="modal" data-bs-target="#answerModal"
          data-id="{{$item->id}}" data-title="{{$item->title}}" data-body="{{$item->body}}">Answer</button>
      </div>
      <div class="fw-semibold mt-2" style="font-size:.88rem;">{{$item->title}}</div>
      <div class="text-muted mt-1" style="font-size:.76rem;">Asked by {{$item->user?->name ?: 'a farmer'}} &middot; {{$item->created_at?->shortRelativeDiffForHumans()}}@if($item->answers_count) &middot; {{$item->answers_count}} {{$item->answers_count==1?'answer':'answers'}} so far @endif</div>
    </div>
    @endif
    @empty
    <div class="av-card mt-0">
      <div class="av-empty"><i class="fa-regular fa-circle-question"></i>
        @if($status == 'answered')You have not answered a question yet.@else No questions to answer right now. New questions from farmers show up here.@endif
      </div>
    </div>
    @endforelse

    {{$items->links('dashboard.partials.pagination')}}

  </div>
  <div class="col-lg-4">
    <div class="av-mini-card mt-0">
      <div class="av-card-title"><i class="fa-solid fa-award"></i> Your Specializations</div>
      @if(count($specializations))
      <div class="d-flex flex-wrap gap-2 mt-2">
        @foreach($specializations as $name)
        <span class="badge-role-expert px-2 py-1 rounded">{{$name}}</span>
        @endforeach
      </div>
      @else
      <div class="text-muted mt-2" style="font-size:.82rem;">No specialization yet. <a href="{{route('expert.profile')}}" class="av-link-more">Add them</a></div>
      @endif
      <div class="text-muted mt-2" style="font-size:.76rem;">Questions tagged in these areas are shown to you first.</div>
    </div>
  </div>
</div>

@include(expertTheme().'partials.answer-modal')

<!-- Read and change an answer -->
<div class="modal fade" id="answerViewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" action="#" id="answerViewForm">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Your answer</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="fw-semibold" id="answerViewTitle" style="font-size:.9rem;"></div>
          <div class="text-secondary mt-1 mb-3" id="answerViewBody" style="font-size:.82rem;"></div>
          <label class="av-form-label">Your answer</label>
          <textarea class="form-control" name="body" id="answerViewText" rows="6" maxlength="5000" required></textarea>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-av-outline" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn-av-primary">Update Answer</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  (function () {
    var updateUrl = @json(route('expert.answerUpdate','__ID__'));

    // The View button of an answered question shows it with the answer.
    var modal = document.getElementById('answerViewModal');
    if (modal) {
      modal.addEventListener('show.bs.modal', function (event) {
        var btn = event.relatedTarget;
        document.getElementById('answerViewForm').action = updateUrl.replace('__ID__', btn.dataset.id);
        document.getElementById('answerViewTitle').textContent = btn.dataset.title;
        document.getElementById('answerViewBody').textContent = btn.dataset.body || '';
        document.getElementById('answerViewText').value = btn.dataset.answer || '';
      });
    }
  })();
</script>
@endpush
