@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Listing Ads')}}</title>
@endsection @push('css')
@include(adminTheme().'services.includes.adListStyle')
@endpush @section('contents')
@php
    $canDelete = isset(json_decode(Auth::user()->permission->permission, true)['services']['delete']);
    $statusLabels = ['active' => 'Active', 'pending' => 'Pending', 'solded' => 'Sold', 'cancelled' => 'Rejected'];
    $tabs = ['' => ['All', $totals->total], 'pending' => ['Pending', $totals->pending], 'active' => ['Active', $totals->active], 'solded' => ['Sold', $totals->solded], 'cancelled' => ['Rejected', $totals->cancelled]];
@endphp

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Listing Ads</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
                    <li class="breadcrumb-item active">Listing Ads</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group">
            <a class="btn btn-outline-primary" href="{{route('admin.servicesSellers')}}" title="Sellers' Ads"><i class="fa-solid fa-users"></i> Sellers</a>
            <a class="btn btn-outline-primary" href="{{route('admin.services')}}" title="Refresh"><i class="fa-solid fa-rotate"></i></a>
        </div>
    </div>
</div>

<div class="content-body">
    <section class="basic-elements">
        <div class="row">
            <div class="col-md-12">
                @include('admin.alerts')
                @if($errors->has('reject_reason'))
                <div class="alert alert-danger">{{$errors->first('reject_reason')}}</div>
                @endif

                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{route('admin.services')}}">
                                @if($seller)<input type="hidden" name="seller" value="{{$seller->id}}" />@endif
                                @if(request()->status)<input type="hidden" name="status" value="{{request()->status}}" />@endif
                                <div class="row">
                                    <div class="col-md-6 mb-1">
                                        <div class="input-group">
                                            <input type="date" name="startDate" value="{{request()->startDate?Carbon\Carbon::parse(request()->startDate)->format('Y-m-d') :''}}" class="form-control" title="From" />
                                            <input type="date" name="endDate" value="{{request()->endDate?Carbon\Carbon::parse(request()->endDate)->format('Y-m-d') :''}}" class="form-control" title="To" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="input-group">
                                            <input type="text" name="search" value="{{request()->search}}" placeholder="Search ads" class="form-control" />
                                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
                        <h4 class="card-title">
                            @if($seller)
                            Ads of <a href="{{route('admin.servicesSeller',$seller->id)}}">{{$seller->name}}</a>
                            <a href="{{route('admin.services',request()->except(['seller','page']))}}" class="btn btn-sm btn-outline-secondary ml-1"><i class="fa-solid fa-xmark"></i> All sellers</a>
                            @else
                            Listing Ads
                            @endif
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{route('admin.services')}}" id="bulkForm">
                                <div class="ad-toolbar">
                                    <ul class="ad-tabs">
                                        @foreach($tabs as $key => [$label, $num])
                                        <li><a href="{{route('admin.services',array_filter(['status'=>$key,'seller'=>$seller?->id]))}}" class="{{(string) request()->status === (string) $key ? 'active' : ''}}">{{$label}} <span class="num">{{$num}}</span></a></li>
                                        @endforeach
                                    </ul>
                                    <div class="ad-bulk">
                                        <select class="form-control form-control-sm" name="action" required>
                                            <option value="">Bulk action</option>
                                            <option value="active">Approve (Active)</option>
                                            <option value="pending">Mark Pending</option>
                                            <option value="solded">Mark Sold</option>
                                            <option value="cancelled">Reject</option>
                                            @if($canDelete)<option value="5">Delete</option>@endif
                                        </select>
                                        <button class="btn btn-sm btn-primary" onclick="return confirm('Apply this action to the selected ads?')">Apply</button>
                                    </div>
                                </div>

                                <div class="ad-list">
                                    <div class="ad-list-head">
                                        <label class="ad-check mb-0" style="cursor: pointer;"><input type="checkbox" id="checkall" class="checkbox" /></label>
                                        <span style="flex: 1;">Ad <span class="checkCounter"></span></span>
                                        <span class="hide-sm" style="width: 190px;">Seller</span>
                                        <span class="hide-sm" style="width: 200px; text-align: right;">Actions</span>
                                    </div>

                                    @forelse($services as $i=>$service)
                                    @include(adminTheme().'services.includes.adRow',['service'=>$service,'number'=>$services->firstItem()+$i])
                                    @empty
                                    <div class="ad-row" style="justify-content: center; color: #8a96a3; padding: 30px;">No ads found.</div>
                                    @endforelse
                                </div>

                                <div class="ad-legend">
                                    <span><i class="fa-regular fa-eye" style="color: #1e9ff2;"></i> View</span>
                                    <span><i class="fa-solid fa-check" style="color: #1ab394;"></i> Approve</span>
                                    <span><i class="fa-solid fa-ban" style="color: #f6a609;"></i> Reject</span>
                                    <span><i class="fa-solid fa-pen" style="color: #666ee8;"></i> Edit</span>
                                    @if($canDelete)<span><i class="fa-regular fa-trash-can" style="color: #ff4961;"></i> Delete</span>@endif
                                </div>

                                <div class="mt-1">{{$services->links('pagination')}}</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include(adminTheme().'services.includes.rejectDialog')

@endsection @push('js')
@endpush
