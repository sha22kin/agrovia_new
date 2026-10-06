@extends(buyerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle($title)}}</title>
@endsection

@section('contents')
@include('dashboard.partials.community-'.$page)
@endsection
