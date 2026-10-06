{{-- One ad card (public marketplace, "Similar products" and the buyer / seller dashboards).
     Styles: public/welcome/css/adCard.css. Needs $ad (with galleryFiles, user, district, thana;
     avg_rating when loaded). Optional: $categoryName, $verified (seller has a verified trade licence),
     $url (where the card links, the public ad page by default), $enquireUrl (adds an "Enquire Now" button). --}}
@php
  $adUrl = $url ?? route('serviceView', $ad->slug ?: 'no-slug');
  $photo = $ad->galleryFiles->first();
  $categoryName = $categoryName ?? null;
  $verified = $verified ?? false;
  $enquireUrl = $enquireUrl ?? null;
@endphp
<div class="agv-ad">
  {{-- The whole photo is shown (contain) over a blurred copy of itself, so nothing is cut off. --}}
  <a href="{{ $adUrl }}" class="agv-ad-media" @if($photo) style="--photo: url('{{ asset($photo->file_url) }}')" @endif>
    @if($photo)
      <img src="{{ asset($photo->file_url) }}" alt="{{ $ad->name }}" loading="lazy" onerror="this.parentNode.classList.add('no-photo');this.remove()">
    @endif
    <svg class="agv-ad-noimg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
    @if($categoryName)
    <span class="agv-ad-chip">{{ $categoryName }}</span>
    @endif
    @if($ad->galleryFiles->count() > 1)
    <span class="agv-ad-photos">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
      {{ $ad->galleryFiles->count() }}
    </span>
    @endif
  </a>
  <div class="agv-ad-body">
    <div class="agv-ad-top">
      <a href="{{ $adUrl }}" class="agv-ad-title" title="{{ $ad->name }}">{{ $ad->name }}</a>
      @if($ad->avg_rating)
      <span class="agv-ad-rating">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.5l2.9 6.1 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.3 1.3-6.6-4.9-4.6 6.6-.8z"/></svg>
        {{ number_format($ad->avg_rating, 1) }}
      </span>
      @endif
    </div>
    <div class="agv-ad-price">
      ৳{{ number_format((float) $ad->price) }}
      @if($ad->negotiable)<span class="agv-ad-nego">Negotiable</span>@endif
    </div>
    <div class="agv-ad-meta">
      @if($ad->area())
      <span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="10" r="2.5"/></svg>
        {{ $ad->area() }}
      </span>
      @endif
      <span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        {{ $ad->created_at->diffForHumans() }}
      </span>
    </div>
    <div class="agv-ad-seller">
      <span class="agv-ad-avatar">{{ Str::upper(Str::substr($ad->user?->name ?: 'S', 0, 1)) }}</span>
      <span class="agv-ad-seller-name">{{ $ad->user?->name ?: 'Seller' }}</span>
      @if($verified)
      <span class="agv-ad-verified" title="Verified seller">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/></svg>
        <span>Verified</span>
      </span>
      @endif
    </div>
    <div class="agv-ad-actions">
      <a href="{{ $adUrl }}" class="agv-ad-btn">View Details
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
      </a>
      @if($enquireUrl)
      <a href="{{ $enquireUrl }}" class="agv-ad-btn alt">Enquire Now</a>
      @endif
    </div>
  </div>
</div>
