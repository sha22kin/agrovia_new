{{-- Stats strip. Needs $items: [label, value, icon, background, color, link text, link url|null] --}}
<div class="av-stats-row">
  <div class="row g-3 g-lg-0 text-center text-sm-start">
    @foreach($items as $i=>[$label,$value,$icon,$bg,$color,$linkText,$linkUrl])
    <div class="col-6 col-lg {{$i>0?'stat-divider':''}}">
      <div class="av-stat">
        <span class="av-stat-icon" style="background:{{$bg}};color:{{$color}};"><i class="fa-solid {{$icon}}"></i></span>
        <div>
          <div class="lbl">{{$label}}</div>
          <div class="val">{{$value}}</div>
          @if($linkUrl)
          <a href="{{$linkUrl}}">{{$linkText}} <i class="fa-solid fa-arrow-right"></i></a>
          @else
          <span class="text-muted" style="font-size:.72rem;">{{$linkText}}</span>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
