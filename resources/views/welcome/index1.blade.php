@extends(welcomeTheme().'layouts.app') 
@section('title')
<title>{{websiteTitle()}}</title>
@endsection 
@section('SEO')
<meta name="title" property="og:title" content="{{general()->meta_title}}" />
<meta name="description" property="og:description" content="{!!general()->meta_description!!}" />
<meta name="keyword" property="og:keyword" content="{{general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset(general()->logo())}}" />
<meta name="url" property="og:url" content="{{route('index')}}" />
<link rel="canonical" href="{{route('index')}}">
@endsection 
@push('css')
<script type="application/ld+json">
    { 
    "@@context": "https://schema.org", 
    "@type": "WebPage", 
    "url": "{{route('index')}}", 
    "name": "{{websiteTitle()}}",
    "author": {
        "@type": "Webpage",
        "name": "{{websiteTitle()}}"
    },
    "description": "{!!general()->meta_description!!}"
    }
</script>
@endpush 

@section('contents')

<!--Slider Part Include Start-->
@include(welcomeTheme().'layouts.slider')

<div class="journyPart">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4>All Category</h4>
                <h2>Why You Should <span style="color: #4cb45c;">Choose</span> Industrial Surplus Ads Site</h2>
            </div>
            <div class="col-md-6">
                <p>
                    Factory Surplus Buysell is the leading marketplace for business surplus, ranging from heavy equipment to industrial machinery in Bangladesh. Explore our all buy & sell listing and save big on top-quality assets today.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="ctgHome">
    <div class="container">
        <div class="row">
            @foreach($category as $hmCtg)
            <div class="col-md-2 col-6"  style="padding: 10px;">
                <a href="{{route('serviceCategory',$hmCtg->slug)}}" class="ctgHomeBox">
                    <img src="{{asset($hmCtg->image())}}" alt="{{$hmCtg->name}}" />
                    <h4>{{$hmCtg->name}}</h4>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="adListsing">
    <div class="container">
        <h2>New Ad Listing</h2>
        <div class="row">
            @foreach($latestServices as $service)
            <div class="col-md-6 col-6" style="padding: 0px 5px;">
                
                <a href="{{route('serviceView',$service->slug?:'no-title')}}" class="listBox">
                    <span>Featured</span>
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{asset($service->fiImage())}}" alt="{{$service->name}}" />
                        </div>
                        <div class="col-md-8">
                            <div class="listContent ctgLst">
                                <h3>{{Str::limit($service->name,70)}}</h3>
                                <h5>{{$service->user?Str::limit($service->user->name,50):'No Author'}}</h5>
                                <p><i class="fa fa-map-marker"></i>{{$service->area()}}</p>
                            </div>
                        </div>
                    </div>
                </a>
                
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="allCtgListing">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="bannerCtgBox">
                    <h1>All Category Listing</h1>
                    <hr>
                    <img src="{{asset('public/welcome/images/allctgBan.jpg')}}" alt="FSBS" />
                </div>
            </div>
            <div class="col-md-8">
                <div class="tabAllCtg">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      @foreach($featuredCategory as $i=>$fctg)
                      <li class="nav-item" role="presentation">
                            <button class="nav-link {{$i==0?'active':''}}" id="ctg_{{$fctg->id}}-tab" data-bs-toggle="tab" data-bs-target="#ctg_{{$fctg->id}}" type="button" role="tab" aria-controls="ctg_{{$fctg->id}}" aria-selected="true">{{$fctg->name}}</button>
                      </li>
                      @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        
                        @foreach($featuredCategory as $i=>$fctg)
                        <div class="tab-pane fade {{$i==0?'show active':''}}" id="ctg_{{$fctg->id}}" role="tabpanel" aria-labelledby="ctg_{{$fctg->id}}-tab">
                            <div class="row">
                                @foreach($fctg->activeListing()->latest()->limit(4)->get() as $listing)
                                <div class="col-md-3 col-6" style="padding: 0 5px;">
                                    
                                    <a href="{{route('serviceView',$listing->slug?:'no-title')}}" class="productGrid">
                                        <img src="{{asset($listing->fiImage())}}" alt="FSBS" />
                                        <h3>{{Str::limit($listing->name,40)}}</h3>
                                        <h5>{{$listing->user?Str::limit($listing->user->name,50):'No Author'}}</h5>
                                        <p>{{$listing->area()}}</p>
                                        <button>View Details</button>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lookingFor">
    <div class="container">
        <h2>Looking For Something?</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="lookingBox">
                    <h4>1,000 Ads in Industrial Surplus</h4>
                    <p>
                        <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a>
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="lookingBox">
                    <h4>1,000 Ads in Industrial Surplus</h4>
                    <p>
                        <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a>
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="lookingBox">
                    <h4>1,000 Ads in Industrial Surplus</h4>
                    <p>
                        <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a> I <a href="#">Generator</a> I <a href="#">Boiler</a> I <a href="#">Factory Machine</a> I <a href="#">Compressor</a> I <a href="#">Yarn</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="freeListPart">
    <div class="container">
        <div class="freeSlick">
            <div class="slick-box">
                <a class="freeGridBox" href="#">
                    <span><i class="fa fa-external-link" aria-hidden="true"></i></span>
                    <img src="{{asset('public/welcome/images/favicon-removebg-preview.png')}}" alt="FSBS" />
                    <h4>Free Listing</h4>
                </a>
            </div>

            <div class="slick-box">
                <a class="freeGridBox" href="#">
                    <span><i class="fa fa-external-link" aria-hidden="true"></i></span>
                    <img src="{{asset('public/welcome/images/freegreen.jpg')}}" alt="FSBS" />
                    <h4>High exposure</h4>
                </a>
            </div>

            <div class="slick-box">
                <a class="freeGridBox" href="#">
                    <span><i class="fa fa-external-link" aria-hidden="true"></i></span>
                    <img src="{{asset('public/welcome/images/freegreen2.jpg')}}" alt="FSBS" />
                    <h4>Secured deals</h4>
                </a>
            </div>

            <div class="slick-box">
                <a class="freeGridBox" href="#">
                    <span><i class="fa fa-external-link" aria-hidden="true"></i></span>
                    <img src="{{asset('public/welcome/images/freegreen3.jpg')}}" alt="FSBS" />
                    <h4>Team support</h4>
                </a>
            </div>

            <div class="slick-box">
                <a class="freeGridBox" href="#">
                    <span><i class="fa fa-external-link" aria-hidden="true"></i></span>
                    <img src="{{asset('public/welcome/images/freegreen4.jpg')}}" alt="FSBS" />
                    <h4>Strong network</h4>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection @push('js') @endpush