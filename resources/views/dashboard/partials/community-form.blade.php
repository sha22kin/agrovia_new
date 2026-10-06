{{-- New community post form. Needs $role. --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">New Post</h5>
  <a href="{{route($role.'.community')}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to Community</a>
</div>

<div class="av-card mt-0">
  <form method="POST" action="{{route($role.'.communityAction','create')}}">
    @csrf
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">Topic <span class="text-danger">*</span></label>
        <select class="form-select @error('tag') is-invalid @enderror" name="tag" required>
          @foreach(\App\Models\CommunityPost::tagsFor(Auth::user()) as $tagName)
          <option value="{{$tagName}}" {{old('tag')==$tagName?'selected':''}}>{{$tagName}}</option>
          @endforeach
        </select>
        @error('tag')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-8">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{old('title')}}" maxlength="191" placeholder="e.g. Paddy prices trending up in Rajshahi" required>
        @error('title')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-12">
        <label class="form-label">Your post <span class="text-danger">*</span></label>
        <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="6" maxlength="3000" placeholder="Share a price, a tip or ask the community" required>{{old('body')}}</textarea>
        @error('body')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
    </div>
    <div class="mt-3">
      <button type="submit" class="btn btn-theme"><i class="fa-solid fa-paper-plane me-1"></i> Publish</button>
      <a href="{{route($role.'.community')}}" class="btn btn-light border ms-1">Cancel</a>
    </div>
  </form>
</div>
