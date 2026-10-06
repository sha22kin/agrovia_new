@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle($page->seo_title?:$page->name)}}</title>
@endsection @section('SEO')   
<meta name="title" property="og:title" content="{{websiteTitle($page->seo_title?:$page->name)}}" />
<meta name="description" property="og:description" content="{!!$page->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($page->image())}}" />
<meta name="url" property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('pageView',$page->slug?:'no-title')}}">
@endsection
@push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$page->name}}</h1>
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
                                <span>Featured</span>
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{asset($service->image())}}" alt="{{$service->name}}" />
                                    </div>
                                    <div class="col-md-9">
                                        <div class="listContent ctgLst">
                                            <h3>{!!Str::limit($service->name,70)!!}</h3>
                                            <h5>{!!Str::limit($service->short_description,50)!!}</h5>
                                            <p><i class="fa fa-map-marker"></i> Gazipur, Dhaka</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="postAdBan">
                    <img src="{{asset('public/welcome/images/smbann.png')}}" alt="FSBS" />
                </div>
            </div>
        </div>
        <!-- pagination -->
        {{$services->links('pagination')}}
    </div>
</div>

@endsection 
@push('js') 
@endpush