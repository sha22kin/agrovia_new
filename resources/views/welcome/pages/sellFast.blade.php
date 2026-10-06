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

<div class="staySafePage">
    <div class="container">
        <div class="careerFirst mt-0">
            <h2><span>Sell Fast</span> on FSBS</h2>
            <p>
                Looking to sell your industrial surplus quickly on FSBS? Follow these tips to create ads that attract more views and serious buyers.
            </p>
        </div>
        <hr>
        <div class="otherContentParts">
            <div class="row">
                <div class="col-md-6">
                    <ul>
                        <li>
                            Add as Much Detail as Possible
                            <p>
                                Detailed ads get more attention! Be sure to include all the important information about the item you’re selling, such as make, model, condition, and specifications. Adding relevant keywords and being honest in your description will make your ad stand out to buyers looking for similar items.
                            </p>
                        </li>
                        <li>
                            Choose the Right Price
                            <p>
                                Once your ad is live and ready to go, consider boosting it to reach more potential buyers. Boosting can increase your ad’s visibility up to 10 times, attracting more views and inquiries. The more demand your ad generates, the better your chances of selling quickly—and at your desired price.
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul>
                        <li>
                            Use Clear, High-Quality Photos
                            <p>
                                Photos make a difference! Ads with real, high-quality images get significantly more views. Use clear, well-lit photos taken from multiple angles to showcase the item’s condition. Avoid stock images—genuine photos build trust with potential buyers.
                            </p>
                        </li>
                        <li>
                            Boost Your Ad’s Visibility
                            <p>
                                Once your ad is live and ready to go, consider boosting it to reach more potential buyers. Boosting can increase your ad’s visibility up to 10 times, attracting more views and inquiries. The more demand your ad generates, the better your chances of selling quickly—and at your desired price.
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>
        <div class="otherContentParts">
            <div class="row">
                <div class="col-md-12">
                    <p>Following these tips will help you connect with more buyers and sell your items faster on FSBS. Happy selling!</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 
@push('js') 
@endpush