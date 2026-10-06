@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle("Sellers' Ads")}}</title>
@endsection @push('css')
<style type="text/css">
    .seller-stats { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 15px; }
    .seller-stats .stat { flex: 1 1 160px; border: 1px solid #e3ebf3; border-radius: 6px; padding: 12px 15px; background: #fff; }
    .seller-stats .stat b { display: block; font-size: 22px; color: #1ab394; }
    .seller-stats .stat.warn b { color: #f9a825; }
    .ad-count { display: inline-block; min-width: 34px; text-align: center; font-weight: 600; }
</style>
@endpush @section('contents')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Sellers' Ads</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.services')}}">Listing Ads</a></li>
                    <li class="breadcrumb-item active">Sellers' Ads</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group">
            <a class="btn btn-outline-primary" href="{{route('admin.servicesSellers')}}">
                <i class="fa-solid fa-rotate"></i>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
                @include('admin.alerts')

                <div class="seller-stats">
                    <div class="stat"><b>{{$totals->sellers}}</b> Sellers with ads</div>
                    <div class="stat"><b>{{$totals->ads}}</b> Ads posted</div>
                    <div class="stat warn"><b>{{$totals->pending}}</b> Ads waiting for approval</div>
                    <div class="stat warn"><b>{{$totals->waitingSellers}}</b> Sellers waiting for approval</div>
                </div>

                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">Sellers and their ads</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{route('admin.servicesSellers')}}" class="row mb-1">
                                <div class="col-md-6 mb-1">
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{request()->search}}" placeholder="Seller name, email or mobile" class="form-control" />
                                        <button type="submit" class="btn btn-success btn-sm rounded-0">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label style="cursor: pointer; margin-top: 8px;">
                                        <input type="checkbox" name="pending" value="1" {{request()->pending?'checked':''}} onchange="this.form.submit()" />
                                        Only sellers with ads waiting for approval
                                    </label>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="min-width: 220px;">Seller</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Pending</th>
                                            <th class="text-center">Active</th>
                                            <th class="text-center">Sold</th>
                                            <th class="text-center">Cancelled</th>
                                            <th style="min-width: 130px;">Last ad</th>
                                            <th style="min-width: 150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sellers as $i=>$seller)
                                        <tr>
                                            <td>{{$sellers->firstItem()+$i}}</td>
                                            <td>
                                                <a href="{{route('admin.servicesSeller',$seller->id)}}"><b>{{$seller->name}}</b></a>
                                                <br />
                                                <small style="color: #888;">{{$seller->email}}{{$seller->mobile?' · '.$seller->mobile:''}}</small>
                                                <br />
                                                <small style="color: #aaa;">Joined {{$seller->created_at?->format('d-m-Y')}}</small>
                                            </td>
                                            <td class="text-center"><a class="ad-count" href="{{route('admin.services',['seller'=>$seller->id])}}">{{$seller->total}}</a></td>
                                            <td class="text-center">
                                                <a class="ad-count" href="{{route('admin.services',['seller'=>$seller->id,'status'=>'pending'])}}">
                                                    @if($seller->pending)
                                                    <span class="badge" style="background-color: #fbc02d;">{{$seller->pending}}</span>
                                                    @else 0 @endif
                                                </a>
                                            </td>
                                            <td class="text-center"><a class="ad-count" href="{{route('admin.services',['seller'=>$seller->id,'status'=>'active'])}}">{{$seller->active}}</a></td>
                                            <td class="text-center"><a class="ad-count" href="{{route('admin.services',['seller'=>$seller->id,'status'=>'solded'])}}">{{$seller->solded}}</a></td>
                                            <td class="text-center"><a class="ad-count" href="{{route('admin.services',['seller'=>$seller->id,'status'=>'cancelled'])}}">{{$seller->cancelled}}</a></td>
                                            <td>
                                                @if($seller->last_ad_at)
                                                {{Carbon\Carbon::parse($seller->last_ad_at)->format('d-m-Y')}}
                                                <br />
                                                <small style="color: #888;">{{Carbon\Carbon::parse($seller->last_ad_at)->diffForHumans()}}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin.servicesSeller',$seller->id)}}" class="btn btn-sm btn-info" title="Seller profile"><i class="fa-regular fa-id-card"></i> Profile</a>
                                                @if($seller->pending)
                                                <a href="{{route('admin.services',['seller'=>$seller->id,'status'=>'pending'])}}" class="btn btn-sm btn-warning">Review</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center" style="color: #888;">No sellers found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{$sellers->links('pagination')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection @push('js') @endpush
