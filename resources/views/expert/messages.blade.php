@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Messages')}}</title>
@endsection

@section('contents')
@include('dashboard.partials.messages')
@endsection
