{{-- Recent enquiries card. Needs $recentEnquiries (loaded withCount('quotes')). --}}
<div class="av-card">
  <div class="d-flex justify-content-between align-items-center">
    <div class="av-card-title"><i class="fa-regular fa-comment-dots"></i> Recent Enquiries</div>
    <a href="{{route('buyer.enquiries')}}" class="av-link-more">View All Enquiries <i class="fa-solid fa-arrow-right"></i></a>
  </div>
  <div class="mt-3">
    @forelse($recentEnquiries as $enquiry)
    @include(buyerTheme().'partials.enquiry-row')
    @empty
    <div class="av-empty"><i class="fa-regular fa-comment-dots"></i>No enquiries yet. <a href="{{route('buyer.enquiriesAction','create')}}">Post a requirement</a></div>
    @endforelse
  </div>
</div>
