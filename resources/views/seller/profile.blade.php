@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('My Profile')}}</title>
@endsection

@section('contents')
<h1 class="av-page-title">My Profile</h1>
<div class="av-card first mt-2">
  <div class="av-card-title mb-3">Profile</div>
  @include('dashboard.profile-form',['action'=>route('seller.profile')])
</div>
@endsection
