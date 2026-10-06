{{-- "Activity" tab. Needs $activity. --}}
<div class="av-card first">
  <div class="av-card-title mb-2"><i class="fa-solid fa-clock-rotate-left"></i> Recent Activity</div>
  @forelse($activity as $item)
  <div class="enquiry-row mt-2">
    <span class="enquiry-ic"><i class="fa-solid {{$item['icon']}}"></i></span>
    <div class="flex-grow-1">
      <div class="fw-semibold" style="font-size:.87rem;">{{$item['title']}}</div>
      @if($item['sub'])<div class="text-muted" style="font-size:.78rem;">{{$item['sub']}}</div>@endif
    </div>
    <div class="order-date">{{$item['date']->format('M d, Y')}}</div>
  </div>
  @empty
  <div class="av-empty"><i class="fa-solid fa-clock-rotate-left"></i>Nothing here yet. Your payments, ads and comments will show up here.</div>
  @endforelse
</div>
