@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{$page->seo_title?:websiteTitle($page->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$page->seo_title?:websiteTitle($page->name)}}" />
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

<div class="bannerAdsPage">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="careerFirst">
                    <h2><span>Advertise</span><br>Your Brand on FSBS</h2>
                    <p>
                        The Premier Platform for Industrial Buy and Sell in Bangladesh!
                    </p>
                    <p>
                        Looking to expand your reach and connect with the right customers for your industrial surplus? FSBS (Factory Surplus Buy & Sell) is Bangladesh's go-to platform for industrial surplus trading, attracting businesses and individuals seeking high-quality, reliable industrial products.
                    </p>
                    <p>
                        With targeted advertising solutions, FSBS helps your brand connect with potential buyers who are actively looking for industrial equipment and materials.
                    </p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="othersImagSection">
                    <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-06.png')}}" alt="FSBS" />
                </div>
            </div>
            <div class="col-md-12 mt-5">
                
                <div class="sliderParts">
                    <div class="slider-bannerads">
                        <div class="slick-box">
                            <h4>Leaderboard</h4>
                        </div>
                        <div class="slick-box">
                            <h4>Skyscraper</h4>
                        </div>
                        <div class="slick-box">
                            <h4>Large Mobile Banner</h4>
                        </div>
                        <div class="slick-box">
                            <h4>Square</h4>
                        </div>
                    </div>
                    
                    <div class="slider-banner mt-5">
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/download.png')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Leaderboard</h5>
                                    <p>
                                        Strategically positioned above the navigation bar on Mobile and below on Desktop, the Leaderboard is viewed by practically every visitor of the site. A great choice for building brand awareness for everyone or in specific categories.
                                    </p>
                                    <p>
                                        Dimensions on Desktop: 970 x 90 px. Dimensions on Mobile: 320 x 50 px.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/download (1).png')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Skyscraper</h5>
                                        <p>
                                            The Skyscraper is a cost-efficient placement on our search results page, with high reach. A good option for brand building.
                                        </p>
                                        <p>
                                            Dimensions on Desktop: 160 x 600 px.
                                        </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/download (2).png')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Large Mobile Banner</h5>
                                        <p>
                                            This large banner has double the height compared to the Leaderboard. And since it's positioned just before pagination links on each search results page, it's viewed by almost everyone.
                                        </p>
                                        <p>
                                            Dimensions on Mobile: 320 x 100 px.
                                        </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/download (3).png')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Square</h5>
                                        <p>
                                            The Square is an optimal placement on each of our ad pages. An effective way to reach a specific target audience when they are reading about the ad.
                                        <p>
                                            Dimensions on Desktop: 250 x 250 px. Dimensions on Mobile: 300 x 250 px.
                                        </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection 
@push('js') 
@endpush