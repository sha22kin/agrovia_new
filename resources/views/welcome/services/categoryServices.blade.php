@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle($category->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{websiteTitle($category->name)}}" />
<meta name="description" property="og:description" content="{!!$category->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$category->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($category->image())}}" />
<meta name="url" property="og:url" content="{{route('serviceCategory',$category->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('serviceCategory',$category->slug?:'no-title')}}">
@endsection 
@push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$category->name}}</h1>
    </div>
</div>

<div class="serviceCompany">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="ctgFilter">
                    <form action="">
                        <div class="filterBox">
                            <label>Sort Result by</label>
                            <select class="form-control">
                                <option value="Latest">Latest</option>
                                <option value="Oldest">Oldest</option>
                            </select>
                        </div>
                        
                        <div class="filterBox">
                            <label>Filter ads by</label>
                            <select class="form-control">
                                <option value="A-Z">A-Z</option>
                                <option value="Z-A">Z-A</option>
                                <option value="Low-High">Low-High</option>
                                <option value="High-Low">High-Low</option>
                            </select>
                        </div>
                        
                        <div class="filterBox">
                            <label>Type of Poster</label>
                            <select class="form-control">
                                <option value="Choose">Choose</option>
                                <option value="Option">Option</option>
                                <option value="Option">Option</option>
                                <option value="Option">Option</option>
                            </select>
                        </div>
                        
                        <div class="filterBox">
                            <label>Location</label>
                            <select class="form-control">
                                <option value="Dhaka">Dhaka</option>
                                <option value="Rajsahi">Rajsahi</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Shylet">Shylet</option>
                            </select>
                        </div>
                        
                        <div class="filterBox">
                            <label>Category</label>
                            <select class="form-control">
                                <option value="Generator">Generator</option>
                                <option value="Boiler">Boiler</option>
                                <option value="Compressor">Compressor</option>
                                <option value="Yarn">Yarn</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="listingParts">
                    <div class="row">
                        @foreach($services as $service)
                        <div class="col-md-12 col-6">
                            <a href="{{route('serviceView',$service->slug?:'no-title')}}" class="listBox">
                                @if($service->fetured)
                                <span>Featured</span>
                                @endif
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{asset($service->fiImage())}}" alt="{{$service->name}}" />
                                    </div>
                                    <div class="col-md-9">
                                        <div class="listContent ctgLst">
                                            <h3>{{Str::limit($service->name,40)}}</h3>
                                            <h5>{{$service->user?Str::limit($service->user->name,50):'No Author'}}</h5>
                                            <p>{{$service->area()}}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <!-- pagination -->
                    {{$services->links('pagination')}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="postAdBan">
                    <img src="{{asset('public/welcome/images/smbann.png')}}" alt="FSBS" />
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection 

@push('js') @endpush