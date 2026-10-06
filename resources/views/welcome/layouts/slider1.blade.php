@if(slider('Front Page Slider'))
<div id="wowslider-container1">
    <div class="ws_images">
        <ul>
            @foreach(slider('Front Page Slider')->subSliders as $i=>$slider)
            <li class="{{$i==0?'active':''}}">
                <img src="{{asset($slider->image())}}" alt="FSBS" title="FSBS" id="wows1_0" />
                <div class="sliderContents" data-aos="fade-up-left">
                    @if($slider->name)  
                    <p>{!!$slider->name!!}</p>
                    @endif
                    @if($slider->description)
                    <h1>{!!$slider->description!!}</h1>
                    @endif
                    @if($slider->seo_title && $slider->seo_description)
                    <a href="{{$slider->seo_description}}" class="btn btn-sm btn-success">{!!$slider->seo_title!!}</a>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<script type="text/javascript" src="{{asset('public/welcome/engine1/wowslider.js')}}"></script>
<script type="text/javascript" src="{{asset('public/welcome/engine1/script.js')}}"></script>
<!-- End WOWSlider.com BODY section -->