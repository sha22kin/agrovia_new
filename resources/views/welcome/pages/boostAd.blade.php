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

<div class="boostAdPage">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="careerFirst marginTop">
                    <h2>Sell Your<br><span>Industrial Surplus</span><br>Faster and Smarter</h2>
                    <p>
                        Looking to sell your surplus items quickly and at the best possible price? Boost Ad on FSBS is the perfect solution to make your listings stand out and attract more buyers in Bangladesh’s premier industrial surplus marketplace.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="othersImagSection">
                    <img src="{{asset('public/welcome/images/FSBSBD Website Mockup sample 2-07.png')}}" alt="FSBS" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="otherContentParts">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Why Choose Boost Ad?</h4>
                            <ul>
                                <li>
                                    - Maximize Views and Responses
                                    <p>
                                        Boost Ad increases your ad’s visibility, ensuring more buyers see it. This means greater engagement and quicker results
                                    </p>
                                </li>
                                <li>
                                    - Sell Faster
                                    <p>
                                        By standing out from the competition, your boosted ad attracts serious buyers, helping you close deals faster.
                                    </p>
                                </li>
                                <li>
                                    - Achieve the Best Price
                                    <p>
                                        More buyer interest means you have the leverage to sell at the best price for your items.
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4>Choose From Multiple Boost Ad Options!</h4>
                            <ul>
                                <li>
                                    - Highlight Your Ad:
                                    <p>
                                        Add a visual pop to grab immediate attention.
                                    </p>
                                </li>
                                <li>
                                    - Feature Your Ad
                                    <p>
                                         Place your ad at the top of its category for maximum exposure.
                                    </p>
                                </li>
                                <li>
                                    - Extended Visibility
                                    <p>
                                        Keep your ad active and prominent for longer durations to reach more potential buyers.
                                    </p>
                                    <p>
                                        Using multiple Boost Ad options together amplifies your ad’s performance, ensuring even faster sales.
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                
                <div class="sliderParts">
                    <div class="slider-boostad">
                        <div class="slick-box">
                            <h4>Top Ad</h4>
                        </div>
                        <div class="slick-box">
                            <h4>Bump Up</h4>
                        </div>
                        <div class="slick-box">
                            <h4>Urgent</h4>
                        </div>
                        <div class="slick-box">
                            <h4>Spotlight</h4>
                        </div>
                        <div class="slick-box">
                            <h4>Schedule ad boost</h4>
                        </div>
                    </div>
                    
                    <div class="slider-boost mt-5">
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/topad.gif')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Top Ad</h5>
                                    <p>
                                        Boost your ad to the top of the regular ads! With Top Ad, it will be visible first insearch results and increase views up to 30x or more.
                                    </p>
                                    <p>
                                        Top Ads are larger, highlighted in yellow, and labeled with 'Top Ad' icon for standout visibility.
                                    </p>
                                    <p>
                                        Get priority placement, ensuring your ad is seen by more buyers first!
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/bump.gif')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Bump Up</h5>
                                        <p>
                                            Apply Bump Up to republish your ad every 12 hours, boosting visibility by up to 20x compared to regular ads! For Bikroy members, the Auto Bump Up feature will automatically republish your ads at 24 or 48-hour intervals, depending on your membership type, ensuring ongoing visibility with no extra effort.
                                        </p>
                                        <p>
                                            Bumped Up ads are labeled with the Bump Up icon, staying near the top of regular listings and moving down as new ads appear.
                                        </p>
                                        <p>
                                            This cycle repeats throughout the promotion duration, keeping your ads visible and fresh.
                                        </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/urgent.gif')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Urgent</h5>
                                        <p>
                                            Want to sell urgently? Apply the Urgent promotion to communicate your urgency to buyers who are looking for a great deal!
                                        </p>
                                        <p>
                                            Urgent ads are highlighted with a bright red marker on the ad.
                                        </p>
                                        <p>
                                            Combine this promotion with a Top Ad or Bump Up to ensure your ad stays on top of regular listings and gets up to 10 times more views.
                                        </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/spotlight.gif')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Spotlight</h5>
                                        <p>
                                            Apply the Spotlight promotion to showcase your high-value items in a large super premium slot right at the top of the ad listings page. Ads promoted with Spotlight attract immediate attention from buyers, so make sure you add great looking photos to your ad!
                                        <p>
                                            Spotlight ads are visually appealing, with big images and a clean design which attracts buyer attention immediately.
                                        </p>
                                        <p>
                                            Each Spotlight ad is given an equal chance of being shown in this super premium spot, with 5 ads being shown automatically to every user.
                                        </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="slick-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{asset('public/welcome/images/boost.gif')}}" alt="FSBS" />
                                </div>
                                <div class="col-md-6">
                                    <h5>Schedule ad boost</h5>
                                        <p>
                                            Our scheduling feature ensures stress-free planning, eliminating last-minute rushes.
                                        <p>
                                            No manual activation is needed, set the date and time, and our system does the rest.
                                        </p>
                                        <p>
                                            Align promotions with events, launches, holidays, and peak buying times.
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