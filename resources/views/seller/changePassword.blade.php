@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Change Password')}}</title>
@endsection

@section('contents')
<h1 class="av-page-title">Change Password</h1>
<div class="av-card first mt-2">
  <div class="av-card-title mb-3">Change Password</div>
  @include('dashboard.password-form',['action'=>route('seller.changePassword')])
</div>
@endsection
