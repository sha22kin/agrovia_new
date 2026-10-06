{{-- The buyer's buying preferences. Needs $preferences (null when never saved) and $summary. --}}
@if($preferences)
@php
  $items = [
    ['fa-wheat-awn','Interested In',$preferences->interests],
    ['fa-award','Preferred Quality',$preferences->quality],
    ['fa-box','Preferred Quantity',$preferences->quantity],
    ['fa-coins','Budget Range',$preferences->budgetLabel($summary['currency'])],
    ['fa-truck','Preferred Delivery',$preferences->delivery],
  ];
@endphp
@foreach($items as [$icon,$label,$value])
<div class="pref-item">
  <span class="pref-ic"><i class="fa-solid {{$icon}}"></i></span>
  <div>
    <div class="pref-label">{{$label}}</div>
    <div class="pref-value">{{$value ?: 'Not set'}}</div>
  </div>
</div>
@endforeach
@else
<div class="av-empty py-3"><i class="fa-solid fa-sliders"></i>You have not set your buying preferences yet. <a href="{{route('buyer.preferences')}}">Set preferences</a></div>
@endif
