@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{$page->seo_title?:websiteTitle($page->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$page->seo_title?:websiteTitle($page->name)}}" />
<meta name="description" property="og:description" content="{!!$page->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($page->image())}}" />
<meta name="url" property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('pageView',$page->slug?:'no-title')}}" />
@endsection @push('css')
<style>
.row {
    gap: 0;
}
</style>
@endpush @section('contents')


<div class="container agv-page-head">
  <div class="agv-eyebrow">KNOWLEDGE HUB</div>
  <h1>Learn what makes a farm profitable</h1>
  <p>Practical, Bangladesh-specific agriculture knowledge — written with experts, free for every farmer.</p>
</div>

<div class="container agv-section">

  <h5 class="section-title">Browse by topic</h5>
  <div class="row g-3 mb-5">
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-leaf"></i></div>
        <span class="en">Farming guides</span>
        <span class="bn">চাষ পদ্ধতি</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-bug"></i></div>
        <span class="en">Crop disease</span>
        <span class="bn">ফসলের রোগ</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-flask"></i></div>
        <span class="en">Fertilizer guide</span>
        <span class="bn">সারের গাইড</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-calendar"></i></div>
        <span class="en">Seasonal farming</span>
        <span class="bn">মৌসুমি চাষ</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-paw"></i></div>
        <span class="en">Livestock care</span>
        <span class="bn">পশু পালন</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-life-ring"></i></div>
        <span class="en">Fish farming</span>
        <span class="bn">মাছ চাষ</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-briefcase"></i></div>
        <span class="en">Business guides</span>
        <span class="bn">ব্যবসার গাইড</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-line-chart"></i></div>
        <span class="en">Market prices</span>
        <span class="bn">বাজার দর</span>
      </a>
    </div>
    <div class="col-lg-2 col-md-3 col-6">
      <a href="#" class="agv-topic-card d-block">
        <div class="agv-topic-icon"><i class="fa fa-university"></i></div>
        <span class="en">Govt. information</span>
        <span class="bn">সরকারি তথ্য</span>
      </a>
    </div>
  </div>

  <h5 class="section-title">Latest articles</h5>
  <div class="row g-4">

    <div class="col-lg-4 col-md-6">
      <div class="agv-article-card">
        <div class="agv-article-media"><i class="fa fa-leaf"></i></div>
        <div class="agv-article-body">
          <div class="agv-article-meta">Farming Tips <span class="dot">·</span> May 25, 2026</div>
          <h6>10 Modern Farming Techniques Every Farmer Should Know</h6>
          <p>From drip irrigation to intercropping — practical methods that raise yield on small plots.</p>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="agv-article-card">
        <div class="agv-article-media"><i class="fa fa-shopping-basket"></i></div>
        <div class="agv-article-body">
          <div class="agv-article-meta">Market Update <span class="dot">·</span> May 22, 2026</div>
          <h6>Vegetable Market Price Update This Week</h6>
          <p>District-wise wholesale rates for potato, onion, tomato and brinjal across 64 districts.</p>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6">
      <div class="agv-article-card">
        <div class="agv-article-media"><i class="fa fa-pagelines"></i></div>
        <div class="agv-article-body">
          <div class="agv-article-meta">Crop Care <span class="dot">·</span> May 20, 2026</div>
          <h6>How to Protect Your Paddy from Common Diseases</h6>
          <p>Identify blast, sheath blight and bacterial leaf streak early, and treat them correctly.</p>
        </div>
      </div>
    </div>

  </div>
</div>




@endsection @push('js') @endpush