{{-- Shared by the buyer and seller dashboards (rendered by dashboard/page.blade.php). Needs $role. --}}
<h5 class="fw-bold mb-1">Saved Searches</h5>
<p class="text-secondary" style="font-size:.85rem;">Get notified when new listings match your saved search criteria.</p>

<div class="av-card mt-0">
  @forelse($searches as $search)
  @php $new = $newCounts[$search->id] ?? 0; @endphp
  <div class="av-search-row {{$loop->last?'':'mb-2'}}">
    <span class="enquiry-ic"><i class="fa-solid fa-magnifying-glass"></i></span>
    <div class="flex-grow-1 overflow-hidden">
      <div class="fw-semibold" style="font-size:.87rem;">{{$search->name}} @if($new)<span class="badge-soft-green ms-1">{{$new > 99 ? '99+' : $new}} new</span>@endif</div>
      <div class="text-muted" style="font-size:.76rem;">Keyword: {{$search->keyword}} · {{$search->criteria($currency)}}</div>
    </div>
    <div class="text-center" style="min-width:90px;">
      <div class="text-muted mb-1" style="font-size:.7rem;">Alerts</div>
      <div class="form-check form-switch d-flex justify-content-center">
        <input class="form-check-input" type="checkbox" role="switch" data-alerts="{{route($role.'.savedSearchAction',['alerts',$search->id])}}" aria-label="Alerts for {{$search->name}}" {{$search->alerts?'checked':''}}>
      </div>
    </div>
    <a href="{{route($role.'.savedSearchRun',$search->id)}}" class="btn-av-outline btn-sm py-1 px-2">Search</a>
    <form action="{{route($role.'.savedSearchAction',['delete',$search->id])}}" method="POST" data-confirm="Delete this saved search?" data-confirm-ok="Yes, delete" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Delete"><i class="fa-solid fa-trash"></i></button></form>
  </div>
  @empty
  <div class="av-empty"><i class="fa-solid fa-magnifying-glass"></i>No saved searches yet. Save one below, or use "Save this search" on the search results page.</div>
  @endforelse
</div>

<div class="av-card">
  <div class="av-section-title"><i class="fa-solid fa-plus"></i> Save a New Search</div>
  @if($searches->count() >= \App\Models\SavedSearch::LIMIT)
  <div class="text-muted" style="font-size:.84rem;">You have saved the most searches allowed ({{\App\Models\SavedSearch::LIMIT}}). Delete one to save another.</div>
  @else
  <form method="POST" action="{{route($role.'.savedSearchAction','save')}}">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label class="av-form-label">Search For</label>
        <input type="text" class="form-control @error('keyword') is-invalid @enderror" name="keyword" value="{{old('keyword')}}" maxlength="120" placeholder="e.g. Paddy, wheat seeds, power tiller" required>
        @error('keyword')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="av-form-label">Name (optional)</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" maxlength="120" placeholder="Leave empty to use the search words">
        @error('name')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="av-form-label">Category</label>
        <select class="form-select @error('category') is-invalid @enderror" name="category">
          <option value="">All categories</option>
          @foreach($categories as $category)
          <option value="{{$category->id}}" {{old('category')==$category->id?'selected':''}}>{{$category->name}}</option>
          @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="av-form-label">Location (district)</label>
        <select class="form-select @error('district') is-invalid @enderror" name="district">
          <option value="">Anywhere</option>
          @foreach($districts as $district)
          <option value="{{$district->id}}" {{old('district')==$district->id?'selected':''}}>{{$district->name}}</option>
          @endforeach
        </select>
        @error('district')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-6 col-md-3">
        <label class="av-form-label">Price from ({{$currency}})</label>
        <input type="number" step="any" min="0" class="form-control @error('min_price') is-invalid @enderror" name="min_price" value="{{old('min_price')}}">
        @error('min_price')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-6 col-md-3">
        <label class="av-form-label">Price up to ({{$currency}})</label>
        <input type="number" step="any" min="0" class="form-control @error('max_price') is-invalid @enderror" name="max_price" value="{{old('max_price')}}">
        @error('max_price')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
    </div>
    <button type="submit" class="btn-av-primary mt-3"><i class="fa-solid fa-plus me-2"></i>Save Search</button>
  </form>
  @endif
</div>


@push('js')
<script>
  (function () {
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // The alerts switch is saved as soon as it is moved; it goes back if the save fails.
    document.querySelectorAll('[data-alerts]').forEach(function (box) {
      box.addEventListener('change', function () {
        var value = box.checked;
        var name = box.getAttribute('aria-label').replace('Alerts for ', '');
        box.disabled = true;
        fetch(box.dataset.alerts, {
          method: 'POST',
          headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json'},
          body: JSON.stringify({value: value ? 1 : 0})
        }).then(function (res) {
          if (!res.ok) { throw new Error('failed'); }
          avToast('success', 'Alerts turned ' + (value ? 'on' : 'off') + ' for "' + name + '"');
        }).catch(function () {
          box.checked = !value;
          avToast('error', 'Could not change the alerts. Please try again');
        }).finally(function () {
          box.disabled = false;
        });
      });
    });
  })();
</script>
@endpush
