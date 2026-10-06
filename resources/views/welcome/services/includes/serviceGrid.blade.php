{{--<div class="serviceGrid">
    <div class="image">
        <img src="{{asset($service->image())}}">
    </div>
    <div class="row m-0">
        <div class="col-8 text">
            <p class="ctg">
                @if($ctg =$service->serviceCategories->first())
                <a href="{{route('serviceCategory',$ctg->slug?:'no-title')}}">{{$ctg->name}}</a>
                @else
                <a href="javascript:void(0)">Undefine</a>
                @endif
            </p>
            <h4><a href="{{route('serviceView',$service->slug?:'no-title')}}">{{$service->name}}</a></h4>
            <p>
            {!!Str::limit($service->short_description,50)!!}
            </p>
        </div>
        <div class="col-4 readMore">
           <a href="{{route('serviceView',$service->slug?:'no-title')}}"> View<br>More<br><i class="fa fa-link"></i></a>
        </div>
    </div>
</div>--}}

<a href="{{route('serviceView',$service->slug?:'no-title')}}" class="productGrid">
    <img src="{{asset($service->image())}}" alt="{{$service->name}}" />
    <h3>{{$service->name}}</h3>
    <h5>{!!Str::limit($service->short_description,50)!!}</h5>
    <p>Gazipur, Dhaka</p>
    <button>View Details</button>
</a>