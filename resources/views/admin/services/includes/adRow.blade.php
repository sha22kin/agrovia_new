{{-- One ad of the admin ads list. Needs $service (with user, category, district) and $number.
     Optional: $showSeller (default true), $selectable (bulk checkbox, default true). --}}
@php
    $canDelete = $canDelete ?? isset(json_decode(Auth::user()->permission->permission, true)['services']['delete']);
    $statusLabels = $statusLabels ?? ['active' => 'Active', 'pending' => 'Pending', 'solded' => 'Sold', 'cancelled' => 'Rejected'];
@endphp
<div class="ad-row">
    <div class="ad-check">
        @if($selectable ?? true)<input class="checkbox" type="checkbox" name="checkid[]" value="{{$service->id}}" /><br />@endif
        {{$number}}
    </div>
    <a href="{{route('admin.servicesAction',['edit',$service->id])}}" class="ad-thumb-wrap" title="{{$service->galleryFiles->count()}} {{Str::plural('photo',$service->galleryFiles->count())}}">
        <img class="ad-thumb" src="{{asset($service->fiImage())}}" alt="" loading="lazy" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'" />
        @if($service->galleryFiles->count() > 1)<span class="ad-thumb-count"><i class="fa-solid fa-images"></i> {{$service->galleryFiles->count()}}</span>@endif
    </a>
    <div class="ad-info">
        <div class="ad-title">
            {{$service->name}}
            <span class="st st-{{$service->status}}">{{$statusLabels[$service->status] ?? 'Draft'}}</span>
            @if($service->fetured)<i class="fa-solid fa-star" style="color: #f6a609;" title="Featured"></i>@endif
        </div>
        <div class="ad-meta">
            @if($service->category)<span><i class="fa-solid fa-tag"></i>{{$service->category->name}}</span>@endif
            @if($service->district)<span><i class="fa-solid fa-location-dot"></i>{{$service->district->name}}</span>@endif
            <span>৳ {{number_format((float) $service->price)}}</span>
            <span><i class="fa-regular fa-calendar"></i>{{$service->created_at->format('d M Y')}}</span>
            <span><i class="fa-regular fa-eye"></i>{{$service->view ?: 0}}</span>
        </div>
        @if($service->status=='cancelled' && $service->reject_reason)
        <div class="ad-reason"><b>Reject reason:</b> {{$service->reject_reason}}</div>
        @endif
    </div>
    @if($showSeller ?? true)
    <div class="ad-seller">
        @if($service->user)
        <a href="{{route('admin.servicesSeller',$service->user->id)}}" title="Seller profile"><i class="fa-regular fa-circle-user"></i> {{Str::limit($service->user->name,24)}}</a>
        <small>{{$service->user->mobile?:$service->user->email}}</small>
        @else
        <span style="color: #aaa;">No seller</span>
        @endif
    </div>
    @endif
    <div class="ad-actions">
        @if($service->status=='active')
        <a href="{{route('serviceView',$service->slug?:'no-slug')}}" target="_blank" class="ib view" title="View in marketplace"><i class="fa-regular fa-eye"></i></a>
        @endif
        @if($service->status!='active')
        <a href="{{route('admin.servicesAction',['approve',$service->id])}}" class="ib ok" title="Approve" onclick="return confirm('Approve this ad? It will go live in the marketplace.')"><i class="fa-solid fa-check"></i></a>
        @endif
        @if($service->status!='cancelled')
        <button type="button" class="ib no js-reject" title="Reject" data-url="{{route('admin.servicesAction',['reject',$service->id])}}" data-name="{{$service->name}}"><i class="fa-solid fa-ban"></i></button>
        @endif
        <a href="{{route('admin.servicesAction',['edit',$service->id])}}" class="ib edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
        @if($canDelete)
        <a href="{{route('admin.servicesAction',['delete',$service->id])}}" class="ib del" title="Delete" onclick="return confirm('Delete this ad? This cannot be undone.')"><i class="fa-regular fa-trash-can"></i></a>
        @endif
    </div>
</div>
