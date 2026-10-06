@extends(buyerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Buying Preferences')}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
  <h5 class="fw-bold mb-0">Buying Preferences</h5>
  <a href="{{route('buyer.profileOverview')}}" class="av-link-more"><i class="fa-solid fa-arrow-left me-1"></i> Back to Profile Overview</a>
</div>

<div class="av-card mt-0">
  <form method="POST" action="{{route('buyer.preferences')}}">
    @csrf
    <div class="row g-3">
      <div class="col-12">
        <label class="form-label">Interested In</label>
        <input type="text" class="form-control @error('interests') is-invalid @enderror" name="interests" value="{{old('interests',$preferences->interests)}}" maxlength="300" placeholder="Paddy, Maize, Potato, Onion (separate with commas)">
        @error('interests')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Preferred Quality</label>
        <select class="form-select @error('quality') is-invalid @enderror" name="quality">
          <option value="">No preference</option>
          @foreach(\App\Models\BuyerPreference::QUALITIES as $option)
          <option value="{{$option}}" {{old('quality',$preferences->quality)==$option?'selected':''}}>{{$option}}</option>
          @endforeach
        </select>
        @error('quality')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Preferred Quantity</label>
        <select class="form-select @error('quantity') is-invalid @enderror" name="quantity">
          <option value="">No preference</option>
          @foreach(\App\Models\BuyerPreference::QUANTITIES as $option)
          <option value="{{$option}}" {{old('quantity',$preferences->quantity)==$option?'selected':''}}>{{$option}}</option>
          @endforeach
        </select>
        @error('quantity')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Preferred Delivery</label>
        <select class="form-select @error('delivery') is-invalid @enderror" name="delivery">
          <option value="">No preference</option>
          @foreach(\App\Models\BuyerPreference::DELIVERIES as $option)
          <option value="{{$option}}" {{old('delivery',$preferences->delivery)==$option?'selected':''}}>{{$option}}</option>
          @endforeach
        </select>
        @error('delivery')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label">Budget from ({{general()->currency ?: '৳'}})</label>
        <input type="number" step="any" min="0" class="form-control @error('budget_min') is-invalid @enderror" name="budget_min" value="{{old('budget_min',$preferences->budget_min!==null?(float) $preferences->budget_min:'')}}" placeholder="10000">
        @error('budget_min')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label">Budget up to ({{general()->currency ?: '৳'}})</label>
        <input type="number" step="any" min="0" class="form-control @error('budget_max') is-invalid @enderror" name="budget_max" value="{{old('budget_max',$preferences->budget_max!==null?(float) $preferences->budget_max:'')}}" placeholder="500000">
        @error('budget_max')<div class="invalid-feedback">{{$message}}</div>@enderror
      </div>
    </div>
    <div class="mt-3">
      <button type="submit" class="btn btn-theme"><i class="fa-solid fa-floppy-disk me-1"></i> Save Preferences</button>
      <a href="{{route('buyer.profileOverview')}}" class="btn btn-light border ms-1">Cancel</a>
    </div>
  </form>
</div>
@endsection
