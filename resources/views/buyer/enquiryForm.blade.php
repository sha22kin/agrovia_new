@extends(buyerTheme().'layouts.app')
@php
  // $enquiry is set when a posted requirement is being edited (My Ads).
  $editing = $enquiry ?? null;
  $heading = $editing ? 'Edit Ad' : 'Post New Enquiry';
  $backUrl = $editing ? route('buyer.ads') : route('buyer.enquiries');
@endphp
@section('title')
<title>{{websiteTitle($heading)}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">{{$heading}}</h5>
  <a href="{{$backUrl}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to {{$editing ? 'My Ads' : 'My Enquiries'}}</a>
</div>

<div class="av-card mt-0">
  <form method="POST" action="{{$editing ? route('buyer.adsAction',['edit',$editing->id]) : route('buyer.enquiriesAction','create')}}">
    @csrf
    <div class="row g-3">
      <div class="col-12">
        <label class="form-label">What are you looking for? <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{old('title',$editing ? $editing->title : request('title'))}}" maxlength="191" placeholder="e.g. Looking for quality paddy" required>
        @error('title')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Category</label>
        <select class="form-select @error('category') is-invalid @enderror" name="category">
          <option value="">Any category</option>
          @foreach($categories as $category)
          <option value="{{$category->id}}" {{old('category',$editing ? $editing->category_id : request('category'))==$category->id?'selected':''}}>{{$category->name}}</option>
          @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-6 col-md-4">
        <label class="form-label">Quantity <span class="text-danger">*</span></label>
        <input type="number" step="any" min="0" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{old('quantity',$editing && $editing->quantity !== null ? (float) $editing->quantity : request('quantity'))}}" placeholder="8000" required>
        @error('quantity')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-6 col-md-4">
        <label class="form-label">Unit <span class="text-danger">*</span></label>
        <select class="form-select @error('unit') is-invalid @enderror" name="unit" required>
          @foreach(\App\Models\Enquiry::UNITS as $unit)
          <option value="{{$unit}}" {{old('unit',$editing ? $editing->unit : (request('unit') ?: 'kg'))==$unit?'selected':''}}>{{$unit}}</option>
          @endforeach
        </select>
        @error('unit')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-12">
        <label class="form-label">Details</label>
        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="5" maxlength="3000" placeholder="Quality, delivery place, expected price or anything else sellers should know">{{old('description',$editing ? $editing->description : request('description'))}}</textarea>
        @error('description')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
    </div>
    <div class="mt-3">
      <button type="submit" class="btn btn-theme"><i class="fa-solid {{$editing ? 'fa-floppy-disk' : 'fa-bullhorn'}} me-1"></i> {{$editing ? 'Save Changes' : 'Post Enquiry'}}</button>
      <a href="{{$backUrl}}" class="btn btn-light border ms-1">Cancel</a>
    </div>
  </form>
</div>
@endsection
