@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('My Listings')}}</title>
@endsection

@section('contents')
<style>
  .ad-reject-note { margin-top: 6px; padding: 6px 9px; border-left: 3px solid #dc3545; background: #fff5f5; border-radius: 0 6px 6px 0; font-size: .78rem; color: #842029; }
</style>
<h1 class="av-page-title">My Listings</h1>
@php
  $badge=['active'=>'success','pending'=>'warning','solded'=>'info','cancelled'=>'danger'];
  $labels=['active'=>'Live','pending'=>'Pending','solded'=>'Sold','cancelled'=>'Rejected'];
@endphp
<div class="av-card first mt-2">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="av-card-title">All ads</div>
    <a href="{{route('seller.listingsAction','create')}}" class="btn btn-theme btn-sm"><i class="fa-solid fa-plus me-1"></i> Post New Ad</a>
  </div>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr><th style="width:50px">SL</th><th style="width:80px">Image</th><th>Title</th><th style="width:120px">Price</th><th style="width:100px">Status</th><th style="width:110px">Date</th><th style="width:110px">Action</th></tr>
      </thead>
      <tbody>
        @foreach($posts as $i=>$post)
        <tr>
          <td>{{$posts->firstItem()+$i}}</td>
          <td><img src="{{asset($post->fiImage())}}" alt="{{$post->name}}" class="thumb" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'"></td>
          <td>
            @if($post->status=='active')
            <a href="{{route('serviceView',$post->slug?:'no-title')}}" target="_blank">{{$post->name}}</a>
            @else
            {{$post->name}}
            @endif
            @if($post->status=='cancelled')
            <div class="ad-reject-note">
              <i class="fa-solid fa-circle-exclamation me-1"></i>
              <b>Rejected by admin:</b> {{$post->reject_reason ?: 'No reason given.'}}
              <a href="{{route('seller.listingsAction',['edit',$post->id])}}" class="ms-1">Fix &amp; resubmit</a>
            </div>
            @elseif($post->status=='pending')
            <div class="text-muted" style="font-size:.75rem;">Waiting for admin approval</div>
            @endif
          </td>
          <td>{{priceFullFormat($post->price)}}</td>
          <td><span class="badge text-bg-{{$badge[$post->status] ?? 'secondary'}}">{{$labels[$post->status] ?? ucfirst($post->status)}}</span></td>
          <td>{{$post->created_at->format('d-m-Y')}}</td>
          <td>
            <a href="{{route('seller.listingsAction',['edit',$post->id])}}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-pen"></i></a>
            <form action="{{route('seller.listingsAction',['delete',$post->id])}}" method="POST" class="d-inline" onsubmit="return confirm('Delete this ad? This cannot be undone.')">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
        @if($posts->count()==0)
        <tr><td colspan="7" class="text-center text-muted py-4">No ad post</td></tr>
        @endif
      </tbody>
    </table>
  </div>
  {{$posts->links('pagination')}}
</div>
@endsection
