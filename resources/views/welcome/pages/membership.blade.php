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

<div class="memberShipPage">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="careerFirst marginTop">
                    <h2><span>Unlock FSBS</span> Membership Benefits</h2>
                    <p>
                        Explore our FSBS membership plans to access exclusive benefits, including Automatic Bump-Up Boosts and Free Top Ad Vouchers—all designed to increase your visibility, attract more buyers, and accelerate your sales growth!
                    </p>
                </div>
                
                <div class="careerFirst">
                    <h2><span>Benefits of an</span> FSBS Being a Member</h2>
                    <p>
                        Unlock the full potential of your sales with FSBS Membership!<br>Here are the key benefits:
                    </p>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <div class="othersImagSection">
                    <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-05.png')}}" alt="FSBS" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="memberLists">
                    <div class="row">
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-10.png')}}" alt="Membership Name" />
                                <h4>Automatic Bump Up Ads</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-11.png')}}" alt="Membership Name" />
                                <h4>Free Top Ads Voucher</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-12.png')}}" alt="Membership Name" />
                                <h4>Add more Images to your Ad</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-13.png')}}" alt="Membership Name" />
                                <h4>Your Own Account on FSBS</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-14.png')}}" alt="Membership Name" />
                                <h4>Verified Seller and Member</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-15.png')}}" alt="Membership Name" />
                                <h4>Ad Analytics</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-16.png')}}" alt="Membership Name" />
                                <h4>Super Charged Ad Discovery</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-17.png')}}" alt="Membership Name" />
                                <h4>Track Buyer Interest</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-18.png')}}" alt="Membership Name" />
                                <h4>Dedicated Customer Support</h4>
                            </a>
                        </div>
                        <div class="col-md-2 col-6"  style="padding: 10px;">
                            <a href="#" class="ctgHomeBox">
                                <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-19.png')}}" alt="Membership Name" />
                                <h4>Build Trust to your Profile</h4>
                            </a>
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