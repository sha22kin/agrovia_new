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
  <div class="agv-eyebrow">COMMUNITY</div>
  <h1>Bangladesh's agriculture community</h1>
  <p>Farmers, buyers, suppliers and experts — asking, answering and trading knowledge every day.</p>
</div>

<div class="container agv-section">
  <div class="row g-4">

    <!-- Sidebar -->
    <div class="col-lg-3">
      <div class="agv-panel">
        <h6>Groups</h6>
        <ul class="agv-group-list">
          <li><a href="#" class="active">All</a></li>
          <li><a href="#">Farmers</a></li>
          <li><a href="#">Buyers</a></li>
          <li><a href="#">Suppliers</a></li>
          <li><a href="#">Experts</a></li>
          <li><a href="#">Marketplace Posts</a></li>
        </ul>
      </div>
    </div>

    <!-- Feed -->
    <div class="col-lg-9">

      <div class="agv-compose">
        <textarea placeholder="Ask a question or share an update with the community..."></textarea>
        <div class="agv-compose-foot">
          <a href="#" class="btn-agv-solid">Post</a>
        </div>
      </div>

      <div class="agv-post">
        <div class="agv-post-head">
          <div class="agv-post-user">
            <div class="agv-avatar">A</div>
            <span>
              <span class="name">Abdul Karim</span>
              <span class="role">Farmer, Bogura</span>
            </span>
          </div>
          <span class="agv-post-tag">Farmers</span>
        </div>
        <div class="agv-post-body">
          ধানের পাতায় বাদামি দাগ দেখা যাচ্ছে, কী করব? এই মৌসুমে আমার নিচু জমির ধানের পাতায় বাদামি দাগ দেখা দিয়েছে। কেউ কি সহায়তা করতে পারবেন?
        </div>
        <div class="agv-post-foot">
          <span><i class="fa fa-heart-o"></i> 32</span>
          <span><i class="fa fa-comment-o"></i> 14 replies</span>
        </div>
      </div>

      <div class="agv-post">
        <div class="agv-post-head">
          <div class="agv-post-user">
            <div class="agv-avatar">M</div>
            <span>
              <span class="name">Metro Super Shop</span>
              <span class="role">Buyer, Dhaka</span>
            </span>
          </div>
          <span class="agv-post-tag">Buyers</span>
        </div>
        <div class="agv-post-title">Looking for regular vegetable suppliers from Jashore</div>
        <div class="agv-post-body">
          We need 2 tons of mixed vegetables weekly. Verified farmers with delivery capacity preferred.
        </div>
        <div class="agv-post-foot">
          <span><i class="fa fa-heart-o"></i> 21</span>
          <span><i class="fa fa-comment-o"></i> 9 replies</span>
        </div>
      </div>

      <div class="agv-post">
        <div class="agv-post-head">
          <div class="agv-post-user">
            <div class="agv-avatar">D</div>
            <span>
              <span class="name">Dr. Farhana Akter</span>
              <span class="role">Livestock Expert</span>
            </span>
          </div>
          <span class="agv-post-tag">Experts</span>
        </div>
        <div class="agv-post-title">শীতে বাচ্চা মুরগির যত্ন নেওয়ার কিছু নিয়ম</div>
        <div class="agv-post-body">
          শীতকালে বাচ্চা মুরগির নিউমোনিয়া ও ঠান্ডাজনিত রোগ প্রতিরোধে যা করবেন...
        </div>
        <div class="agv-post-foot">
          <span><i class="fa fa-heart-o"></i> 88</span>
          <span><i class="fa fa-comment-o"></i> 26 replies</span>
        </div>
      </div>

    </div>
  </div>
</div>


@endsection @push('js') @endpush