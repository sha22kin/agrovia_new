{{-- A page shared by the buyer and seller dashboards. Needs $layout, $title, $partial (a view in dashboard/partials) and $role. --}}
@extends($layout)
@section('title')
<title>{{websiteTitle($title)}}</title>
@endsection

@section('contents')
@include('dashboard.partials.'.$partial)
@endsection
