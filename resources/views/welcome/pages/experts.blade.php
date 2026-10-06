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



<div class="xp-page">
<div class="container agv-section">

  <div class="xp-section-head xp-toolbar">
    <div>
      <h2>Available experts</h2>
      <p>Book a paid consultation, online or on-site.</p>
    </div>
    {{-- Field filter: shows only the experts whose specialty matches (option value is a regex). --}}
    <label class="xp-select">
      <span class="visually-hidden">Filter experts by field</span>
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M7 12h10"/><path d="M10 18h4"/></svg>
      <select id="xpField">
        <option value="">All experts</option>
        <option value="crop">Crop</option>
        <option value="soil|fertili">Soil</option>
        <option value="livestock|veterinar|cattle">Livestock</option>
        <option value="fisher|aqua|fish">Fisheries</option>
        <option value="poultry|chicken">Poultry</option>
        <option value="disease|patholog|pest">Plant Disease</option>
      </select>
    </label>
  </div>

  <div class="expert-grid" id="xpGrid">
    @foreach(config('expert.showcase', []) as $ex)
      @include(welcomeTheme().'services.includes.expertCard', ['ex' => $ex])
    @endforeach
  </div>
  <p class="xp-empty" id="xpEmpty" hidden>No experts in this field yet. Please check back soon.</p>

  <div class="xp-section-head xp-gap">
    <h2>Agricultural services</h2>
    <p>Verified service providers, district by district.</p>
  </div>

  <div class="row g-3">
    <div class="col-lg-4 col-sm-6">
      <div class="agv-service-card">
        <div class="agv-service-left">
          <div class="agv-service-icon"><i class="fa fa-flask"></i></div>
          <span><span class="en">Soil Testing</span><span class="bn">মাটি পরীক্ষা</span></span>
        </div>
        <a href="#" class="agv-request-btn">Request</a>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="agv-service-card">
        <div class="agv-service-left">
          <div class="agv-service-icon"><i class="fa fa-clipboard"></i></div>
          <span><span class="en">Farm Consultation</span><span class="bn">খামার পরামর্শ</span></span>
        </div>
        <a href="#" class="agv-request-btn">Request</a>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="agv-service-card">
        <div class="agv-service-left">
          <div class="agv-service-icon"><i class="fa fa-cogs"></i></div>
          <span><span class="en">Machinery Rental</span><span class="bn">যন্ত্রপাতি ভাড়া</span></span>
        </div>
        <a href="#" class="agv-request-btn">Request</a>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="agv-service-card">
        <div class="agv-service-left">
          <div class="agv-service-icon"><i class="fa fa-truck"></i></div>
          <span><span class="en">Transportation</span><span class="bn">পরিবহন</span></span>
        </div>
        <a href="#" class="agv-request-btn">Request</a>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="agv-service-card">
        <div class="agv-service-left">
          <div class="agv-service-icon"><i class="fa fa-snowflake-o"></i></div>
          <span><span class="en">Cold Storage</span><span class="bn">হিমাগার</span></span>
        </div>
        <a href="#" class="agv-request-btn">Request</a>
      </div>
    </div>
    <div class="col-lg-4 col-sm-6">
      <div class="agv-service-card">
        <div class="agv-service-left">
          <div class="agv-service-icon"><i class="fa fa-paw"></i></div>
          <span><span class="en">Veterinary Services</span><span class="bn">পশু চিকিৎসা</span></span>
        </div>
        <a href="#" class="agv-request-btn">Request</a>
      </div>
    </div>
  </div>

</div>
</div>

@endsection @push('js')
<script>
  // Field dropdown: show only matching experts.
  (function () {
    var select = document.getElementById('xpField');
    var cards = document.querySelectorAll('#xpGrid .expert-card');
    var empty = document.getElementById('xpEmpty');
    select.addEventListener('change', function () {
      var re = select.value ? new RegExp(select.value, 'i') : null;
      var shown = 0;
      cards.forEach(function (card) {
        var role = card.querySelector('.role');
        var ok = !re || re.test(role ? role.textContent : '');
        card.hidden = !ok;
        if (ok) shown++;
      });
      empty.hidden = shown > 0;
    });
  })();
</script>
@endpush