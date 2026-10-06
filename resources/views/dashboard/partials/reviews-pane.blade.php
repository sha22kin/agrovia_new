{{-- "Reviews" tab. Needs $reviews. --}}
<div class="av-card first">
  <div class="av-card-title mb-2"><i class="fa-regular fa-star"></i> Reviews &amp; Comments</div>
  @forelse($reviews as $review)
  <div class="enquiry-row mt-2">
    <span class="enquiry-ic"><i class="fa-regular {{$review->type==0?'fa-star':'fa-comment'}}"></i></span>
    <div class="flex-grow-1">
      <div class="fw-semibold" style="font-size:.87rem;">{{$review->post?->name ?: 'Deleted post'}}</div>
      @if($review->type==0 && $review->rating>0)
      <div class="stars">@for($i=1;$i<=5;$i++)<i class="fa-{{$i<=$review->rating?'solid':'regular'}} fa-star"></i>@endfor</div>
      @endif
      <div class="text-muted" style="font-size:.8rem;">{{\Illuminate\Support\Str::limit(strip_tags($review->content),140)}}</div>
    </div>
    <div class="text-end">
      <span class="order-status status-{{strtolower($review->status)}}">{{ucfirst($review->status)}}</span>
      <div class="order-date">{{$review->created_at?->format('M d, Y')}}</div>
    </div>
  </div>
  @empty
  <div class="av-empty"><i class="fa-regular fa-star"></i>No reviews or comments yet.</div>
  @endforelse
</div>
