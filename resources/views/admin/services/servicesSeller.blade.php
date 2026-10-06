@extends(adminTheme().'layouts.app') @section('title')
<title>{{websiteTitle($seller->name.' - Seller')}}</title>
@endsection @push('css')
@include(adminTheme().'services.includes.adListStyle')
<style type="text/css">
    .sp-head { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; }
    .sp-avatar { width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid #e3f7f1; flex: 0 0 auto; }
    .sp-name { font-size: 20px; font-weight: 700; color: #2b3a4a; margin: 0; overflow-wrap: anywhere; }
    .sp-sub { display: flex; flex-wrap: wrap; gap: 4px 16px; margin-top: 6px; font-size: 13px; color: #6b7785; }
    .sp-sub i { color: #1ab394; margin-right: 4px; }
    .sp-flag { display: inline-block; padding: 2px 9px; border-radius: 10px; font-size: 11.5px; font-weight: 600; margin-left: 6px; vertical-align: middle; }
    .sp-flag.ok { background: #e3f7f1; color: #12896f; }
    .sp-flag.off { background: #ffe8eb; color: #c62839; }

    .sp-stats { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 10px; margin-bottom: 20px; }
    .sp-stat { display: block; border: 1px solid #e3ebf3; border-radius: 8px; padding: 12px; background: #fff; color: #4a5568; text-align: center; }
    .sp-stat:hover { border-color: #1ab394; color: #4a5568; }
    .sp-stat.active { border-color: #1ab394; box-shadow: 0 0 0 2px #e3f7f1; }
    .sp-stat b { display: block; font-size: 24px; line-height: 1.2; }
    .sp-stat span { font-size: 12.5px; }

    .sp-info { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .sp-table { width: 100%; font-size: 13px; }
    .sp-table th { width: 42%; color: #8a96a3; font-weight: 500; padding: 6px 8px 6px 0; vertical-align: top; }
    .sp-table td { color: #2b3a4a; padding: 6px 0; overflow-wrap: anywhere; }
    .sp-doc { display: flex; justify-content: space-between; gap: 10px; padding: 7px 0; border-bottom: 1px dashed #eef2f6; font-size: 13px; }

    @media (max-width: 991.98px) {
        .sp-stats { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .sp-info { grid-template-columns: 1fr; }
    }
    @media (max-width: 479.98px) {
        .sp-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
</style>
@endpush @section('contents')
@php
    $statLinks = [
        [null, 'Total ads', $counts->total, '#2b3a4a'],
        ['active', 'Active', $counts->active, '#12896f'],
        ['pending', 'Pending', $counts->pending, '#a86d00'],
        ['solded', 'Sold', $counts->solded, '#7b1fa2'],
        ['cancelled', 'Rejected', $counts->cancelled, '#c62839'],
    ];
    $docBadge = ['verified' => ['Verified', 'ok'], 'rejected' => ['Rejected', 'off']];
    $address = collect([$seller->address_line1, $seller->address_line2, $seller->cityN?->name, $seller->districtN?->name, $seller->divitionN?->name])->filter()->implode(', ');
@endphp

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Seller Profile</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.servicesSellers')}}">Sellers' Ads</a></li>
                    <li class="breadcrumb-item active">{{Str::limit($seller->name, 30)}}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
        <div class="btn-group float-md-right" role="group">
            <a class="btn btn-outline-primary" href="{{route('admin.services',['seller'=>$seller->id])}}"><i class="fa-solid fa-list"></i> All ads list</a>
        </div>
    </div>
</div>

<div class="content-body">
    @include('admin.alerts')
    @if($errors->has('reject_reason'))
    <div class="alert alert-danger">{{$errors->first('reject_reason')}}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="sp-head">
                <img class="sp-avatar" src="{{$seller->avatar(160)}}" alt="{{$seller->name}}" onerror="this.onerror=null;this.src='{{asset('public/medies/profile.png')}}'" />
                <div style="flex: 1; min-width: 0;">
                    <h4 class="sp-name">
                        {{$seller->name}}
                        @if($seller->deactivated_at)
                        <span class="sp-flag off">Deactivated</span>
                        @else
                        <span class="sp-flag ok">Active account</span>
                        @endif
                    </h4>
                    <div class="sp-sub">
                        <span><i class="fa-solid fa-id-badge"></i>SEL-{{str_pad($seller->id, 5, '0', STR_PAD_LEFT)}}</span>
                        @if($seller->email)<span><i class="fa-regular fa-envelope"></i>{{$seller->email}}</span>@endif
                        @if($seller->mobile)<span><i class="fa-solid fa-phone"></i>{{$seller->mobile}}</span>@endif
                        <span><i class="fa-regular fa-calendar"></i>Joined {{$seller->created_at?->format('d M Y')}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sp-stats">
        @foreach($statLinks as [$key, $label, $num, $color])
        <a class="sp-stat {{$status === $key ? 'active' : ''}}" href="{{route('admin.servicesSeller', array_filter(['id' => $seller->id, 'status' => $key]))}}">
            <b style="color: {{$color}};">{{$num}}</b><span>{{$label}}</span>
        </a>
        @endforeach
        <div class="sp-stat"><b style="color: #1e9ff2;">{{number_format($counts->views)}}</b><span>Ad views</span></div>
    </div>

    <div class="sp-info">
        <div class="card mb-0">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;"><h4 class="card-title"><i class="fa-regular fa-user"></i> Contact details</h4></div>
            <div class="card-body">
                <table class="sp-table">
                    <tr><th>Name</th><td>{{$seller->name}}</td></tr>
                    <tr><th>Email</th><td>{{$seller->email ?: '—'}} @if($seller->email_verified_at)<span class="sp-flag ok">Verified</span>@endif</td></tr>
                    <tr><th>Mobile</th><td>{{$seller->mobile ?: '—'}} @if($seller->verify_code_status)<span class="sp-flag ok">Verified</span>@endif</td></tr>
                    <tr><th>Company</th><td>{{$seller->company_name ?: '—'}}</td></tr>
                    <tr><th>Address</th><td>{{$address ?: '—'}}</td></tr>
                    <tr><th>Last ad</th><td>{{$counts->last_ad_at ? Carbon\Carbon::parse($counts->last_ad_at)->format('d M Y').' ('.Carbon\Carbon::parse($counts->last_ad_at)->diffForHumans().')' : '—'}}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mb-0">
            <div class="card-header" style="border-bottom: 1px solid #e3ebf3;"><h4 class="card-title"><i class="fa-solid fa-briefcase"></i> Business details</h4></div>
            <div class="card-body">
                @if($business)
                <table class="sp-table">
                    <tr><th>Business type</th><td>{{$business->business_type ?: '—'}}</td></tr>
                    <tr><th>Trade license no.</th><td>{{$business->license_no ?: '—'}}</td></tr>
                    <tr><th>TIN / BIN</th><td>{{$business->tin_bin ?: '—'}}</td></tr>
                    <tr><th>Established</th><td>{{$business->established_year ?: '—'}}</td></tr>
                    <tr><th>Employees</th><td>{{$business->employees ?: '—'}}</td></tr>
                    <tr><th>Business address</th><td>{{$business->address ?: '—'}}</td></tr>
                    <tr><th>Service areas</th><td>{{$business->areas ?: '—'}}</td></tr>
                </table>
                @else
                <p class="mb-1" style="color: #8a96a3;">The seller has not added business details yet.</p>
                @endif

                <h6 class="mt-1 mb-50" style="font-weight: 600;">Documents</h6>
                @forelse($documents as $doc)
                <div class="sp-doc">
                    <span>{{$doc->type}} <small style="color: #8a96a3;">· {{$doc->created_at?->format('d M Y')}}</small></span>
                    @php [$docLabel, $docClass] = $docBadge[$doc->status] ?? ['Under review', '']; @endphp
                    <span class="sp-flag {{$docClass}}" @if(!$docClass) style="background: #fff4d6; color: #a86d00;" @endif>{{$docLabel}}</span>
                </div>
                @empty
                <p class="mb-0" style="color: #8a96a3; font-size: 13px;">No documents uploaded.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">
                {{$status ? ['active'=>'Active','pending'=>'Pending','solded'=>'Sold','cancelled'=>'Rejected'][$status].' ads' : 'All ads'}}
                <small style="color: #8a96a3;">({{$sellerAds->total()}})</small>
            </h4>
        </div>
        <div class="card-body">
            <div class="ad-list">
                @forelse($sellerAds as $i => $service)
                @include(adminTheme().'services.includes.adRow', ['service' => $service, 'number' => $sellerAds->firstItem() + $i, 'showSeller' => false, 'selectable' => false])
                @empty
                <div class="ad-row" style="justify-content: center; color: #8a96a3; padding: 30px;">No ads here.</div>
                @endforelse
            </div>
            <div class="mt-1">{{$sellerAds->links('pagination')}}</div>
        </div>
    </div>
</div>

@include(adminTheme().'services.includes.rejectDialog')

@endsection @push('js') @endpush
