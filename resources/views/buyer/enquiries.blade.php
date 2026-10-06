@extends(buyerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('My Enquiries')}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">My Enquiries</h5>
  <a href="{{route('buyer.enquiriesAction','create')}}" class="btn-av-primary"><i class="fa-solid fa-plus me-2"></i>Post New Enquiry</a>
</div>
<div class="d-flex gap-2 flex-wrap mb-3 mt-2">
  @foreach(['all'=>'All']+\App\Models\Enquiry::FILTERS as $key=>$label)
  <a href="{{$key=='all'?route('buyer.enquiries'):route('buyer.enquiries',['status'=>$key])}}" class="av-cat-pill {{($status ?: 'all')==$key?'active':''}}">{{$label}} ({{number_format($counts[$key])}})</a>
  @endforeach
</div>

<div class="av-card mt-0">
  @forelse($enquiries as $enquiry)
  @include(buyerTheme().'partials.enquiry-row')
  @empty
  <div class="av-empty"><i class="fa-regular fa-comment-dots"></i>No enquiries found. <a href="{{route('buyer.enquiriesAction','create')}}">Post a requirement</a></div>
  @endforelse

  {{$enquiries->links('dashboard.partials.pagination')}}
</div>
@endsection
