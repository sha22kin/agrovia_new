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

<div class="aboutUsPageFirst">
    <div class="container">
        <p>
            FSBS is Bangladesh’s leading platform dedicated to the buying and selling of industrial surplus and equipment. We serve as a vital marketplace for businesses, industries, and individuals who are looking to buy or sell surplus machinery, equipment, and industrial materials. FSBS was founded with a mission to create a simple and reliable space for the industrial sector, en- suring that surplus resources find new purposes rather than going to waste. Whether you’re in construction, manufacturing, logistics, or any industry in need of quality equipment, FSBS connects you directly with the right suppliers and buyers across Bangladesh.<p>
        </p>
        <p>
            We understand the value of cost-efficient industrial solutions, and our goal is to empower businesses with easy access to affordable surplus equipment and materials. FSBS offers a streamlined, user-friendly experience where you can post ads, browse listings, and communi- cate with sellers or buyers seamlessly. Join our community to explore quality surplus options and make profitable connections in Bangladesh’s industrial marketplace.
        </p>
    </div>
</div>

<div class="sellBuyPart">
    <div class="container">
        <div class="row">
            <div class="col-md-6" style="padding: 15px;">
                <h3>Have items to sell?</h3>
                <p>
                    Sign up for a free account to start selling your items! It takes less than 2 minutes to post an ad. Visit How to sell fast for some tips on creating great ads that generate a lot of buyer interest. If you have a lot of items to sell, consider buying a member- ship and enjoy additional benefits. We also have some great tools that help make your ad stand out from the rest. Check out the Ad Promotions page for more information.
                </p>
            </div>
            <div class="col-md-6" style="padding: 15px;">
                <h3 class="mobileMar">Looking to buy something?</h3>
                <p>
                    Discover a wide variety of industrial surplus items at competitive prices. Whether you’re in construction, manufacturing, logistics, or another industry, FSBS connects you directly with verified sellers across Bangladesh. Find quality equip- ment and materials that meet your needs and budget all in one place.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="valuePart">
    <div class="container">
        <h3>Values</h3>
        <p><b>Integrity:</b> We are committed to providing a transparent, trust worthy platform where buyers and sellers can connect confidently.</p>
        <p><b>Sustainability:</b> By promoting the reuse of industrial equipment and materials, we strive to reduce waste and promote sustainable practices across industries.</p>
        <p><b>Community:</b> FSBS is dedicated to supporting and strengthening the industrial community in Bangladesh, fostering connections and economic growth.</p>
        <p><b>Efficiency:</b> We prioritize user experience, ensuring our platform is fast, accessible, and easy to use for all members.</p>
        <p><b>Quality:</b> We strive to maintain high standards across our platform by promoting listings of reliable and well-maintained industrial surplus.</p>
    </div>
</div>

<div class="misVisPart">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="misVisBox">
                    <h3>Mission</h3>
                    <p>
                        To create a trusted and efficient marketplace in Bangladesh for industrial surplus, where businesses and individuals can buy and sell quality equipment and materials sustainably and affordably. We aim to support industries by offering a platform that connects surplus resources with new owners, reducing waste and enhancing the economic value of industrial assets.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="misVisBox">
                    <h3>Vision</h3>
                    <p>
                        To become Bangladesh’s most reliable and widely-used platform for industrial surplus, empowering industries with sustainable buying and selling solutions. FSBS envisions a future where surplus resources are optimized across the industrial sector, fostering a more sustainable and cost-effective economy.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 
@push('js') 
@endpush


