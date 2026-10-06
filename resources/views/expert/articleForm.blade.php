@extends(expertTheme().'layouts.app')
@php
  $editing = $post ?? null;
  $heading = $editing ? 'Edit Article' : 'Write New Article';
  $published = $editing && $editing->status == 'active';
@endphp
@section('title')
<title>{{websiteTitle($heading)}}</title>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
@endpush

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">{{$heading}}</h5>
  <a href="{{route('expert.articles')}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to My Articles</a>
</div>

<form method="POST" action="{{$editing ? route('expert.articleAction',['edit',$editing->id]) : route('expert.articleAction','create')}}" enctype="multipart/form-data">
  @csrf
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="av-card mt-0">
        <div class="mb-3">
          <label class="form-label">Title <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name',$editing?->name)}}" maxlength="191" placeholder="e.g. 5 Signs Your Soil Needs Urea Sooner Than You Think" required>
          @error('name')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Short summary</label>
          <textarea class="form-control @error('short_description') is-invalid @enderror" name="short_description" rows="2" maxlength="500" placeholder="One or two lines shown in the article lists">{{old('short_description',$editing?->short_description)}}</textarea>
          @error('short_description')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
        <div>
          <label class="form-label">Article</label>
          <textarea id="articleBody" class="form-control @error('description') is-invalid @enderror" name="description" rows="14">{{old('description',$editing?->description)}}</textarea>
          @error('description')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="av-mini-card mt-0">
        <div class="av-card-title"><i class="fa-solid fa-paper-plane"></i> Publish</div>
        <div class="text-muted mt-2" style="font-size:.8rem;">
          @if($editing)Status: <span class="badge-status {{$published?'badge-active':'badge-pending'}}">{{$published?'Published':'Draft'}}</span>@else A new article starts as a draft until you publish it.@endif
        </div>
        <div class="d-grid gap-2 mt-3">
          <button type="submit" name="publish" value="1" class="btn-av-primary"><i class="fa-solid fa-check me-2"></i>{{$published?'Update Article':'Publish'}}</button>
          <button type="submit" name="draft" value="1" class="btn-av-outline"><i class="fa-regular fa-floppy-disk me-2"></i>{{$published?'Move to Drafts':'Save as Draft'}}</button>
        </div>
      </div>

      <div class="av-mini-card">
        <div class="av-card-title"><i class="fa-regular fa-image"></i> Cover Image</div>
        @if($editing && $editing->imageFile)
        <img src="{{asset($editing->image())}}" alt="{{$editing->name}}" class="w-100 mt-2" style="border-radius:10px;max-height:150px;object-fit:cover;">
        @endif
        <input type="file" class="form-control form-control-sm mt-2 @error('image') is-invalid @enderror" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
        <div class="text-muted mt-1" style="font-size:.72rem;">JPG, PNG or WebP, up to 4 MB. It is saved as WebP.</div>
        @error('image')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
      </div>

      <div class="av-mini-card">
        <div class="av-card-title"><i class="fa-solid fa-folder-open"></i> Categories</div>
        @forelse($categories as $category)
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" name="categories[]" value="{{$category->id}}" id="cat{{$category->id}}" {{in_array($category->id,(array) old('categories',$selected))?'checked':''}}>
          <label class="form-check-label" for="cat{{$category->id}}" style="font-size:.84rem;">{{$category->name}}</label>
        </div>
        @empty
        <div class="text-muted mt-2" style="font-size:.8rem;">No blog category yet.</div>
        @endforelse
        @error('categories.*')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
      </div>
    </div>
  </div>
</form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
  // A simple editor: text formatting, lists and links. Images are added with the cover image.
  $('#articleBody').summernote({
    height: 340,
    placeholder: 'Write your article here...',
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['ul', 'ol']],
      ['insert', ['link']],
      ['view', ['codeview']]
    ],
    styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote']
  });
</script>
@endpush
