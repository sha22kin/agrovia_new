@extends(buyerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('My Ads')}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">My Ads</h5>
  <a href="{{route('buyer.enquiriesAction','create')}}" class="btn-av-primary"><i class="fa-solid fa-plus me-2"></i>Post New Requirement</a>
</div>
<p class="text-secondary" style="font-size:.85rem;">Buying requirements you've posted for sellers to quote on.</p>

<div class="av-card mt-0">
  @forelse($ads as $ad)
  @php [$statusText,$statusClass] = $ad->adStatus(); @endphp
  <div class="av-ad-row {{$loop->last?'':'mb-2'}}">
    <span class="enquiry-ic"><i class="fa-solid fa-bullhorn"></i></span>
    <div class="flex-grow-1 overflow-hidden">
      <div class="fw-semibold" style="font-size:.87rem;"><a href="{{route('buyer.enquiriesAction',['view',$ad->id])}}" class="text-reset text-decoration-none">{{$ad->title}}</a></div>
      <div class="text-muted" style="font-size:.78rem;">
        @if($ad->quantityLabel())Quantity: {{$ad->quantityLabel()}} &middot; @endif{{$ad->quotes_count}} {{$ad->quotes_count==1?'quote':'quotes'}}
      </div>
      @if($ad->isRejected())
      <div class="text-danger" style="font-size:.76rem;">Not approved: {{Str::limit($ad->reject_reason ?: 'No reason given.',120)}} — edit it to send again.</div>
      @elseif($ad->isPending())
      <div class="text-muted" style="font-size:.76rem;">Waiting for admin approval before sellers can see it.</div>
      @endif
    </div>
    <span class="badge-status {{$statusClass}}">{{$statusText}}</span>
    <div class="order-date text-end" style="min-width:90px;">{{$ad->created_at?->format('M d, Y')}}</div>
    @if(in_array($statusText,['Closed','Expired']))
    <form action="{{route('buyer.adsAction',['renew',$ad->id])}}" method="POST">@csrf<button type="submit" class="btn-av-outline btn-sm py-1 px-2" title="Post it again for {{\App\Models\Enquiry::EXPIRY_DAYS}} days"><i class="fa-solid fa-rotate"></i></button></form>
    @endif
    <a href="{{route('buyer.adsAction',['edit',$ad->id])}}" class="btn-av-outline btn-sm py-1 px-2" title="Edit"><i class="fa-solid fa-pen"></i></a>
    <form action="{{route('buyer.adsAction',['delete',$ad->id])}}" method="POST" data-confirm="Delete this ad and its quotes?" data-confirm-ok="Yes, delete" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Delete"><i class="fa-solid fa-trash"></i></button></form>
  </div>
  @empty
  <div class="av-empty"><i class="fa-solid fa-bullhorn"></i>You have not posted any requirement yet. <a href="{{route('buyer.enquiriesAction','create')}}">Post one now</a></div>
  @endforelse

  {{$ads->links('dashboard.partials.pagination')}}
</div>
@endsection
