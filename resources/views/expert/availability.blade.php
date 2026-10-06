@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Availability')}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">Availability</h5>
  <button type="button" class="btn-av-primary" data-bs-toggle="modal" data-bs-target="#slotModal"><i class="fa-solid fa-plus me-2"></i>Add Time Slot</button>
</div>
<p class="text-secondary" style="font-size:.85rem;">Set the days and hours farmers can book a consultation with you.</p>

<div class="row g-3">
  @foreach(\App\Models\ExpertAvailability::DAYS as $day => $name)
  @php $daySlots = $slots->get($day, collect()); @endphp
  <div class="col-6 col-md-4 col-lg-3">
    <div class="av-avail-day {{$daySlots->isEmpty()?'off':''}}">
      <div class="d-flex justify-content-between align-items-start">
        <div class="fw-semibold mb-2">{{$name}}</div>
        <button type="button" class="av-post-stat" title="Add a slot on {{$name}}" data-bs-toggle="modal" data-bs-target="#slotModal" data-day="{{$day}}"><i class="fa-solid fa-plus"></i></button>
      </div>
      @forelse($daySlots as $slot)
      <span class="av-avail-slot">{{$slot->label()}}
        <form action="{{route('expert.availabilitySlotDelete',$slot->id)}}" method="POST" class="d-inline" data-confirm="Remove {{$slot->label()}} on {{$name}}?" data-confirm-ok="Yes, remove" data-confirm-danger>@csrf<button type="submit" class="av-avail-x" title="Remove"><i class="fa-solid fa-xmark"></i></button></form>
      </span>
      @empty
      <div style="font-size:.78rem;">Not Available</div>
      @endforelse
    </div>
  </div>
  @endforeach
</div>

<div class="av-card">
  <div class="av-section-title"><i class="fa-solid fa-video"></i> Consultation Settings</div>
  <form method="POST" action="{{route('expert.availabilitySettings')}}">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="av-form-label">Session Length</label>
        <select class="form-select @error('session_minutes') is-invalid @enderror" name="session_minutes">
          @foreach(\App\Models\ExpertProfile::SESSION_MINUTES as $minutes)
          <option value="{{$minutes}}" {{old('session_minutes',$profile->session_minutes ?: 30)==$minutes?'selected':''}}>{{$minutes}} minutes</option>
          @endforeach
        </select>
        @error('session_minutes')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="av-form-label">Consultation Fee</label>
        <div class="input-group">
          <span class="input-group-text">{{$currency}}</span>
          <input type="number" step="any" min="0" class="form-control @error('fee') is-invalid @enderror" name="fee" value="{{old('fee',$profile->fee !== null ? (float) $profile->fee : '')}}" placeholder="500">
          <span class="input-group-text">/ session</span>
        </div>
        @error('fee')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
      </div>
    </div>
    <button type="submit" class="btn-av-primary mt-3">Save Settings</button>
  </form>
</div>

<!-- Add a time slot -->
<div class="modal fade" id="slotModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" action="{{route('expert.availabilitySlotAdd')}}">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Add Time Slot</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="av-form-label">Days</label>
          <div class="d-flex flex-wrap gap-2 mb-1" id="slotDays">
            @foreach(\App\Models\ExpertAvailability::DAYS as $day => $name)
            <div>
              <input type="checkbox" class="btn-check" name="days[]" value="{{$day}}" id="day{{$day}}" {{in_array($day,array_map('intval',(array) old('days',[])))?'checked':''}}>
              <label class="btn btn-sm btn-outline-success" for="day{{$day}}">{{substr($name,0,3)}}</label>
            </div>
            @endforeach
          </div>
          @error('days')<div class="invalid-feedback d-block mb-2">{{$message}}</div>@enderror
          @error('days.*')<div class="invalid-feedback d-block mb-2">{{$message}}</div>@enderror

          <div class="row g-3 mt-1">
            <div class="col-6">
              <label class="av-form-label">From</label>
              <input type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{old('start_time','10:00')}}" required>
              @error('start_time')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
            <div class="col-6">
              <label class="av-form-label">To</label>
              <input type="time" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{old('end_time','12:00')}}" required>
              @error('end_time')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-av-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-av-primary">Add Slot</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  (function () {
    var modalEl = document.getElementById('slotModal');

    // The + of a day card opens the form with that day chosen; "Add Time Slot" starts with no day chosen.
    modalEl.addEventListener('show.bs.modal', function (event) {
      var btn = event.relatedTarget;
      if (!btn) { return; }
      modalEl.querySelectorAll('#slotDays input').forEach(function (box) {
        box.checked = btn.dataset.day !== undefined && box.value === btn.dataset.day;
      });
    });

    @if($errors->has('days') || $errors->has('days.*') || $errors->has('start_time') || $errors->has('end_time'))
    // The form was sent back with a problem: show it again with what was chosen.
    new bootstrap.Modal(modalEl).show();
    @endif
  })();
</script>
@endpush
