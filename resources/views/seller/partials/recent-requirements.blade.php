{{-- Latest buyer requirements card. Needs $requirements (with user, category, quotes_count, own quote). --}}
<div class="av-card">
  <div class="d-flex justify-content-between align-items-center">
    <div class="av-card-title"><i class="fa-regular fa-comment-dots"></i> Latest Buyer Requirements</div>
    <a href="{{route('seller.enquiries')}}" class="av-link-more">View All <i class="fa-solid fa-arrow-right"></i></a>
  </div>
  <div class="mt-3">
    @forelse($requirements as $enquiry)
    @include(sellerTheme().'partials.requirement-row')
    @empty
    <div class="av-empty"><i class="fa-regular fa-comment-dots"></i>No open requirements right now. Buyers' requirements you can quote on show up here.</div>
    @endforelse
  </div>
</div>
