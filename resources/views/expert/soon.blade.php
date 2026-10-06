@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle($title)}}</title>
@endsection

@section('contents')
<h5 class="fw-bold mb-3">{{$title}}</h5>
<div class="av-card mt-0">
  <div class="av-empty"><i class="fa-solid fa-hammer"></i>This page is being built. It will be here soon.
    <a href="{{route('expert.dashboard')}}">Back to the dashboard</a></div>
</div>
@endsection
