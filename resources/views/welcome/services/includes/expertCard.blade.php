{{-- One expert card (home page and Experts page). Needs $ex: name, role, degree, place, years, price, rating.
     Long text is clamped (full text in the tooltip) so the card keeps its shape. --}}
@php
  // "Dr. Nazmul Hasan" -> "NH": initials of the name without titles.
  $initials = collect(preg_split('/\s+/u', preg_replace('/^((Dr|Md|Mst|Prof|Professor|Engr|Mr|Mrs|Ms)\.?\s+)+/i', '', trim($ex['name']))))
      ->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('') ?: '?';
  $placeLine = trim(($ex['place'] ?? '').(!empty($ex['years']) ? ' · '.$ex['years'].' '.\Illuminate\Support\Str::plural('year', (int) $ex['years']).' experience' : ''), ' ·');
@endphp
<div class="expert-card">
  <div class="expert-head">
    <span class="expert-avatar" aria-hidden="true">{{ $initials }}</span>
    <div class="expert-id">
      <h4 title="{{ $ex['name'] }}">{{ $ex['name'] }}</h4>
      @if(!empty($ex['role']))<div class="role" title="{{ $ex['role'] }}">{{ $ex['role'] }}</div>@endif
    </div>
  </div>

  <ul class="expert-facts">
    @if(!empty($ex['degree']))
    <li title="{{ $ex['degree'] }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.42 10.92a1 1 0 0 0-.02-1.84L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.83l8.57 3.91a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
      <span>{{ $ex['degree'] }}</span>
    </li>
    @endif
    @if($placeLine !== '')
    <li title="{{ $placeLine }}">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12Z"/><circle cx="12" cy="10" r="2.5"/></svg>
      <span>{{ $placeLine }}</span>
    </li>
    @endif
  </ul>

  <div class="expert-bottom">
    <div class="expert-price">৳{{ number_format($ex['price'] ?? 0) }} <span>/ session</span></div>
    @if(!empty($ex['rating']))
    <div class="expert-rating">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.5l2.9 6.1 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.3 1.3-6.6-4.9-4.6 6.6-.8z"/></svg>
      {{ number_format($ex['rating'], 1) }}
    </div>
    @endif
  </div>
  <a href="{{ $bookUrl ?? '#' }}" class="btn-book">Book consultation</a>
</div>
