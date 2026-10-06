@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Buyer Requirements')}}</title>
@endsection

@section('contents')
@php
  // The pills and the category filter keep each other's choice.
  $carryStatus = $status ? ['status'=>$status] : [];
  $carryCategory = $category ? ['category'=>$category->id] : [];
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">Buyer Requirements</h5>
  <form method="GET" action="{{route('seller.enquiries')}}" class="d-flex gap-2">
    @if($status)<input type="hidden" name="status" value="{{$status}}">@endif
    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Category">
      <option value="">All categories</option>
      @foreach($categories as $item)
      <option value="{{$item->id}}" {{$category && $category->id==$item->id?'selected':''}}>{{$item->name}}</option>
      @endforeach
    </select>
  </form>
</div>
<p class="text-secondary" style="font-size:.85rem;">Buyers post what they need. Send a quote and they can choose you.</p>

<div class="d-flex gap-2 flex-wrap mb-3">
  @foreach(['all'=>'All','new'=>'Not quoted yet','quoted'=>'Quoted by me'] as $key=>$label)
  <a href="{{route('seller.enquiries',$carryCategory + ($key=='all'?[]:['status'=>$key]))}}" class="av-cat-pill {{($status ?: 'all')==$key?'active':''}}">{{$label}} ({{number_format($counts[$key])}})</a>
  @endforeach
</div>

<div class="av-card mt-0">
  @forelse($requirements as $enquiry)
  @include(sellerTheme().'partials.requirement-row')
  @empty
  <div class="av-empty"><i class="fa-regular fa-comment-dots"></i>No requirements found{{$category?' in '.$category->name:''}}. @if($status || $category)<a href="{{route('seller.enquiries')}}">Show all</a>@endif</div>
  @endforelse

  {{$requirements->links('dashboard.partials.pagination')}}
</div>
@endsection
