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

<div class="faqPage">
    <div class="container">
        <div class="careerFirst mt-0">
            <h2><span>Frequently</span> Asked Questions</h2>
            <p>
                Get the most out of our site. Contact us if you don’t find what you’re looking for.
            </p>
        </div>
        <hr>
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample">
                    What is FSBS?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    FSBS (Factory Surplus Buying & Selling) is a platform dedicated to the industrial sector in Bangladesh, allowing businesses and individuals to buy and sell surplus equipment, materials, machinery, and other industrial items. It provides a space for users to post classified ads and connect with potential buyers or sellers within the industry.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample1">
                    Who can use FSBS?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample1">
                <div class="card card-body">
                    FSBS is open to manufacturers, factories, industries, resellers, and individuals who are looking to buy or sell surplus industrial items. Whether you’re a large factory with excess inventory or a small business in need of affordable equipment, FSBS is designed to help you connect with the right buyers or sellers.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample2">
                    How do I post an ad on FSBS?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample2">
                <div class="card card-body">
                    To post an ad, register or log in to your FSBS account. Once logged in, click on "Post an Ad" and fill in the details about your item, such as the title, description, price, location, and photos. After submitting, your ad will be reviewed by our team for approval to ensure quality and relevance.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample900">
                    How much does it cost to post an ad?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample900">
                <div class="card card-body">
                    Posting a basic ad on FSBS is free. However, we offer premium features, such as ad promotion and featured listings, to enhance visibility for a small fee. This can help increase your chances of attracting potential buyers faster.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample4">
                    How do I contact a seller?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample4">
                <div class="card card-body">
                    Each ad includes the seller’s contact information or a “Contact Seller” option. You can send them a message directly through FSBS or use the contact information provided to call or email them.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample5">
                    Can I edit or delete my ad?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample5">
                <div class="card card-body">
                    Yes, you can edit or delete your ad at any time. Simply log in to your FSBS account, go to "My Ads," and select the option to edit or delete the ad you want to modify.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample3">
                    How long will my ad be active?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample3">
                <div class="card card-body">
                    Ads remain active for 30 days from the date of posting. After this period, they will expire but can be renewed by logging in to your account and selecting the option to renew.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample6">
                    Is there a way to promote my ad?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample6">
                <div class="card card-body">
                    Yes, FSBS offers ad promotion options to increase your ad’s visibility. You can choose from featured listings, highlighted ads, or top-of-page placements to make your listing stand out to more users.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample7">
                    What should I do if I encounter a scam or suspicious listing?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample7">
                <div class="card card-body">
                    FSBS prioritizes user safety, but if you suspect a scam or find a listing that seems suspicious, please report it immediately using the “Report” button on the ad page. Our team will review and take appropriate action to maintain a safe and trustworthy marketplace.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample8">
                    How do I pay for an item I want to purchase?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample8">
                <div class="card card-body">
                    FSBS does not handle payments between buyers and sellers. Once you’ve connected with the seller and agreed on terms, you can arrange payment directly. We advise using secure payment methods and meeting in a safe location when finalizing transactions.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample9">
                    Is FSBS available on mobile?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample9">
                <div class="card card-body">
                    Yes, FSBS is mobile-friendly, allowing you to browse, post ads, and connect with buyers or sellers on your smartphone. You can access FSBS through your mobile browser and enjoy the same features as on a desktop.
                </div>
            </div>
        </div>
        
        <div class="faqParts">
            <p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample10">
                    How can I contact FSBS customer support?<span><i class="fa fa-angle-down"></i></span>
                </button>
            </p>
            <div class="collapse" id="collapseExample10">
                <div class="card card-body">
                    For assistance, you can reach out to our customer support team through our Contact Us page. We’re here to help you with any questions or issues you may have while using the platform.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 
@push('js') 
@endpush