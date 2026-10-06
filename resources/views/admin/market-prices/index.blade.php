@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Market Prices')}}</title>
@endsection

@push('css')
<style type="text/css">
  .mp-board{display:flex;flex-wrap:wrap;gap:10px;}
  .mp-board .mp-item{border:1px solid #e3ebf3;border-radius:6px;padding:8px 12px;min-width:160px;}
  .mp-board .mp-item small{color:#6b7f75;}
  .mp-up{color:#1a7a3f;}
  .mp-down{color:#d14343;}
</style>
@endpush
@section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
     <h3 class="content-header-title mb-0">Market Prices</h3>
     <div class="row breadcrumbs-top">
       <div class="breadcrumb-wrapper col-12">
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a>
           </li>
           <li class="breadcrumb-item active">Market Prices</li>
         </ol>
       </div>
     </div>
   </div>
   <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
     <div class="btn-group float-md-right" role="group">
       	<a class="btn btn-outline-primary" href="{{route('admin.marketPrices')}}">
       		<i class="fa-solid fa-rotate"></i>
       	</a>
     </div>
   </div>
</div>

<div class="content-body">
  <section class="basic-elements">
    <div class="row">
      <div class="col-md-12">
        @include(adminTheme().'alerts')
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">{{$edit ? 'Edit Price' : 'Publish Today\'s Price'}}</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <form action="{{route('admin.marketPricesSave',$edit?->id)}}" method="post">
                @csrf
                <div class="form-group">
                  <label>Item Name (English)*</label>
                  <input type="text" name="name" list="mpItems" value="{{old('name',$edit?->name)}}" placeholder="Paddy" class="form-control {{$errors->has('name')?'error':''}}" required>
                  <datalist id="mpItems">
                    @foreach($items as $item)
                    <option value="{{$item->name}}" data-bn="{{$item->name_bn}}" data-icon="{{$item->icon}}" data-unit="{{$item->unit}}"></option>
                    @endforeach
                  </datalist>
                  @error('name')<span class="text-danger small">{{$message}}</span>@enderror
                </div>
                <div class="form-group">
                  <label>Item Name (Bangla)</label>
                  <input type="text" name="name_bn" value="{{old('name_bn',$edit?->name_bn)}}" placeholder="ধান" class="form-control">
                </div>
                <div class="row">
                  <div class="col-6 form-group">
                    <label>Icon (emoji)</label>
                    <input type="text" name="icon" value="{{old('icon',$edit?->icon)}}" placeholder="🌾" class="form-control">
                  </div>
                  <div class="col-6 form-group">
                    <label>Unit*</label>
                    <input type="text" name="unit" value="{{old('unit',$edit?->unit ?: 'kg')}}" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6 form-group">
                    <label>Price (৳)*</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{old('price',$edit?->price)}}" class="form-control {{$errors->has('price')?'error':''}}" required>
                    @error('price')<span class="text-danger small">{{$message}}</span>@enderror
                  </div>
                  <div class="col-6 form-group">
                    <label>Date*</label>
                    <input type="date" name="price_date" max="{{date('Y-m-d')}}" value="{{old('price_date',$edit?->price_date?->format('Y-m-d') ?: date('Y-m-d'))}}" class="form-control {{$errors->has('price_date')?'error':''}}" required>
                    @error('price_date')<span class="text-danger small">{{$message}}</span>@enderror
                  </div>
                </div>
                <button type="submit" class="btn btn-success">Save Price</button>
                @if($edit)
                <a href="{{route('admin.marketPrices')}}" class="btn btn-outline-secondary">Cancel</a>
                @endif
              </form>
              <p class="small text-muted mt-1 mb-0">The home page shows each item's latest price. The change % is worked out from the item's previous price.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card">
          <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Live on Home Page</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              @if(count($board))
              <div class="mp-board">
                @foreach($board as $item)
                <div class="mp-item">
                  <div>{{$item['icon']}} {{$item['name']}} @if($item['name_bn'])<small>({{$item['name_bn']}})</small>@endif</div>
                  <div>৳{{number_format($item['price'],2)}}/{{$item['unit']}}
                    @if($item['change'] !== null)
                    <span class="{{$item['change'] < 0 ? 'mp-down' : 'mp-up'}}">{{$item['change'] < 0 ? '↓' : '↑'}} {{abs($item['change'])}}%</span>
                    @endif
                  </div>
                  <small>{{\Carbon\Carbon::parse($item['date'])->format('d M Y')}}</small>
                </div>
                @endforeach
              </div>
              @else
              <p class="mb-0 text-muted">No prices yet. Add one and it appears on the home page right away.</p>
              @endif
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Price History</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <form action="{{route('admin.marketPrices')}}" class="mb-1">
                <div class="input-group" style="max-width:360px;">
                  <input type="text" name="search" value="{{request()->search}}" placeholder="Item name" class="form-control">
                  <button type="submit" class="btn btn-success btn-sm rounded-0">Search</button>
                </div>
              </form>
              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Item</th>
                      <th>Price</th>
                      <th style="width:150px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($prices as $price)
                    <tr>
                      <td>{{$price->price_date->format('d-m-Y')}}</td>
                      <td>{{$price->icon}} {{$price->name}} @if($price->name_bn)<small class="text-muted">({{$price->name_bn}})</small>@endif</td>
                      <td>৳{{number_format($price->price,2)}}/{{$price->unit}}</td>
                      <td>
                        <a href="{{route('admin.marketPrices',['edit'=>$price->id])}}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{route('admin.marketPricesDelete',$price->id)}}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this price?')">Delete</button>
                        </form>
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">No prices found.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              {{$prices->links('pagination')}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@endsection
@push('js')
<script>
  // Picking a known item fills its Bangla name, icon and unit.
  $('input[name=name]').on('change', function(){
    var opt = $('#mpItems option').filter((i, o) => o.value === this.value).first();
    if(!opt.length) return;
    ['name_bn','icon','unit'].forEach(function(f){
      var input = $('input[name='+f+']');
      if(!input.val() || f === 'unit') input.val(opt.data(f === 'name_bn' ? 'bn' : f));
    });
  });
</script>
@endpush
