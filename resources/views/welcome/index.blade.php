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
    "@context": "https://schema.org", 
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



<section class="categories">
  <div class="container">
    <div class="section-head">
      <div>
        <h2>Marketplace categories</h2>
        <p>Everything an agricultural business needs, in one marketplace.</p>
      </div>
      <a class="view-all" href="{{route('pageView','marketplace')}}">Browse all →</a>
    </div>
    <div class="cat-grid">
      @foreach($homeCategories as $ctg)
      <a class="cat-tile" href="{{route('serviceCategory',$ctg->slug ?: 'no-title')}}">
        <span class="cat-thumb">
          @if($ctg->imageFile)
          <img src="{{asset($ctg->image())}}" alt="{{$ctg->name}}" loading="lazy">
          @else
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
          @endif
        </span>
        <h3>{{$ctg->name}}</h3>
      </a>
      @endforeach
    </div>
  </div>
</section>

<section class="featured">
  <div class="container">
    <div class="section-head">
      <div>
        <h2>Featured products</h2>
        <p>Live listings from verified farmers, suppliers and agro companies.</p>
      </div>
      <a class="view-all" href="#">View all →</a>
    </div>

    <div class="row">
      @forelse($latestServices as $service)
      @php $adUrl = route('serviceView',$service->slug); @endphp
      <div class="product-card">
        <a href="{{$adUrl}}" class="product-thumb">
          @if($service->galleryFiles->isNotEmpty())
            <img src="{{asset($service->fiImage())}}" alt="{{$service->name}}" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.replaceWith(Object.assign(document.createElement('span'),{textContent:'🌱'}));">
          @else
            <span>🌱</span>
          @endif
        </a>
        <div class="product-body">
          <div class="product-top">
            <div class="product-name">
              <h4><a href="{{$adUrl}}">{{$service->name}}</a></h4>
              @if($service->category)<div class="bn">{{$service->category->name}}</div>@endif
            </div>
            @if($service->avg_rating)
            <div class="product-rating"><span class="star">★</span> {{number_format($service->avg_rating,1)}}</div>
            @endif
          </div>
          <div class="product-price">৳{{number_format($service->price)}}</div>
          <div class="product-meta">
            @if($service->area())<div class="loc">📍 {{$service->area()}}</div>@endif
            {{Str::limit($service->short_description,70)}}
          </div>
          <div class="product-tags">
            <span class="tag">{{$service->user && $service->user->company_name ? 'Company' : 'Farmer'}}</span>
            @if($service->sellerVerified)<span class="tag verified">✓ Verified</span>@endif
          </div>
          <div class="product-seller">By {{$service->user ? ($service->user->company_name ?: $service->user->name) : 'Agroviea Seller'}}</div>
          <a href="{{$adUrl}}" class="btn-contact" style="display:block;text-align:center;">Contact Seller</a>
        </div>
      </div>
      @empty
      <p class="text-secondary" style="padding:20px 0;">No featured ads yet. Once sellers post live ads, they'll show up here.</p>
      @endforelse
    </div>
  </div>
</section>

<section class="farmers">
  <div class="container">
    <div class="section-head" style="display:block;">
      <h2>Direct from farmers</h2>
      <p>Buy straight from verified farms — no middleman, fair price for both sides.</p>
    </div>

    <div class="row">
      <div class="farmer-card">
        <div class="farmer-head">
          <div class="farmer-avatar">🥕</div>
          <div class="farmer-name">
            <h4>Abdul Karim Farm</h4>
            <div class="loc">📍 Shibganj, Bogura</div>
          </div>
        </div>
        <div class="farmer-info">
          <div>
            <div class="label">Type</div>
            <div class="value">Vegetable Farmer</div>
          </div>
          <div>
            <div class="label">Farm size</div>
            <div class="value">5 acres</div>
          </div>
        </div>
        <div class="farmer-products">
          <div class="label">Products</div>
          <div class="value">Potato, Tomato, Cauliflower</div>
        </div>
        <div class="farmer-footer">
          <span class="verified-badge">✓ Verified farmer</span>
          <button class="btn-profile">View profile</button>
        </div>
      </div>

      <div class="farmer-card">
        <div class="farmer-head">
          <div class="farmer-avatar">🌾</div>
          <div class="farmer-name">
            <h4>Salma Begum Agro</h4>
            <div class="loc">📍 Sadar, Jashore</div>
          </div>
        </div>
        <div class="farmer-info">
          <div>
            <div class="label">Type</div>
            <div class="value">Paddy &amp; Pulses</div>
          </div>
          <div>
            <div class="label">Farm size</div>
            <div class="value">3.5 acres</div>
          </div>
        </div>
        <div class="farmer-products">
          <div class="label">Products</div>
          <div class="value">Paddy, Lentil, Mustard</div>
        </div>
        <div class="farmer-footer">
          <span class="verified-badge">✓ Verified farmer</span>
          <button class="btn-profile">View profile</button>
        </div>
      </div>

      <div class="farmer-card">
        <div class="farmer-head">
          <div class="farmer-avatar">🐄</div>
          <div class="farmer-name">
            <h4>Rashed Dairy</h4>
            <div class="loc">📍 Sirajganj</div>
          </div>
        </div>
        <div class="farmer-info">
          <div>
            <div class="label">Type</div>
            <div class="value">Dairy Farmer</div>
          </div>
          <div>
            <div class="label">Farm size</div>
            <div class="value">40 cattle</div>
          </div>
        </div>
        <div class="farmer-products">
          <div class="label">Products</div>
          <div class="value">Milk, Cattle, Organic manure</div>
        </div>
        <div class="farmer-footer">
          <span class="verified-badge">✓ Verified farmer</span>
          <button class="btn-profile">View profile</button>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bulk-cta">
  <div class="container">
    <div class="bulk-cta-inner">
      <div class="row">
        <div class="bulk-copy">
          <h2>Need agricultural products in bulk?</h2>
          <p>Post one requirement and receive quotes from farmers, suppliers and agro companies across all 64 districts. Compare price, quantity and delivery in one place.</p>
          <button class="btn-post-req">Post your requirement</button>
        </div>
        <div class="bulk-stats">
          <div class="stat-card">
            <div class="num">50K+</div>
            <div class="label">Farmers</div>
          </div>
          <div class="stat-card">
            <div class="num">20K+</div>
            <div class="label">Buyers</div>
          </div>
          <div class="stat-card">
            <div class="num">500+</div>
            <div class="label">Suppliers</div>
          </div>
          <div class="stat-card">
            <div class="num">64</div>
            <div class="label">Districts covered</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="rfqs">
  <div class="container">
    <div class="section-head">
      <div>
        <h2>Open buyer requirements</h2>
        <p>Sellers can respond with a quote in minutes.</p>
      </div>
      <a class="view-all" href="#">All RFQs →</a>
    </div>

    <div class="row">
      <div class="rfq-card">
        <h4>10,000 kg Potato needed in Dhaka</h4>
        <div class="rfq-meta">Metro Super Shop · Dhaka · <span class="dim">Delivery within 7 days</span></div>
        <div class="rfq-tags">
          <span class="rfq-tag qty">10,000 kg</span>
          <span class="rfq-tag">Budget ৳20 – ৳24/kg</span>
          <span class="rfq-tag">12 quotes</span>
        </div>
        <button class="btn-submit-quote">Submit quote</button>
      </div>

      <div class="rfq-card">
        <h4>Bulk Rui &amp; Katla fish for restaurant chain</h4>
        <div class="rfq-meta">Spice Garden Restaurants · Chattogram · <span class="dim">Recurring monthly</span></div>
        <div class="rfq-tags">
          <span class="rfq-tag qty">1,500 kg / month</span>
          <span class="rfq-tag">Budget ৳250 – ৳300/kg</span>
          <span class="rfq-tag">5 quotes</span>
        </div>
        <button class="btn-submit-quote">Submit quote</button>
      </div>

      <div class="rfq-card">
        <h4>Organic compost for 20 acre farm</h4>
        <div class="rfq-meta">Green Valley Agro · Rangpur · <span class="dim">Delivery within 15 days</span></div>
        <div class="rfq-tags">
          <span class="rfq-tag qty">40 tons</span>
          <span class="rfq-tag">Budget Negotiable</span>
          <span class="rfq-tag">8 quotes</span>
        </div>
        <button class="btn-submit-quote">Submit quote</button>
      </div>

      <div class="rfq-card">
        <h4>Maize supply for feed mill</h4>
        <div class="rfq-meta">Nutri Feed Industries · Gazipur · <span class="dim">Delivery within 30 days</span></div>
        <div class="rfq-tags">
          <span class="rfq-tag qty">80,000 kg</span>
          <span class="rfq-tag">Budget ৳28 – ৳31/kg</span>
          <span class="rfq-tag">17 quotes</span>
        </div>
        <button class="btn-submit-quote">Submit quote</button>
      </div>
    </div>
  </div>
</section>

<section class="experts">
  <div class="container">
    <div class="section-head">
      <div>
        <h2>Agricultural experts</h2>
        <p>Get guidance from agronomists, vets, soil and fisheries specialists.</p>
      </div>
      <a class="view-all" href="{{route('pageView','experts')}}">All experts →</a>
    </div>

    <div class="expert-grid">
      @foreach(config('expert.showcase', []) as $ex)
        @include(welcomeTheme().'services.includes.expertCard', ['ex' => $ex, 'bookUrl' => route('pageView','experts')])
      @endforeach
    </div>
  </div>
</section>

<section class="services">
  <div class="container">
    <div class="section-head" style="display:block;">
      <h2>Popular agricultural services</h2>
    </div>

    @php
      // Line icons (24×24, stroke) for each service.
      $services = [
        ['Soil Testing', 'মাটি পরীক্ষা', '<path d="M10 2v7.527a2 2 0 0 1-.211.896L4.72 20.55a1 1 0 0 0 .9 1.45h12.76a1 1 0 0 0 .9-1.45l-5.069-10.127A2 2 0 0 1 14 9.527V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/>'],
        ['Farm Consultation', 'ফার্ম পরামর্শ', '<rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>'],
        ['Machinery Rental', 'যন্ত্র ভাড়া', '<path d="m10 11 11 .9a1 1 0 0 1 .8 1.1l-.665 4.158a1 1 0 0 1-.988.842H20"/><path d="M16 18h-5"/><path d="M18 5a1 1 0 0 0-1 1v5.573"/><path d="M3 4h8.129a1 1 0 0 1 .99.863L13 11.246"/><path d="M4 11V4"/><path d="M7 15h.01"/><path d="M8 10.1V4"/><circle cx="18" cy="18" r="2"/><circle cx="7" cy="15" r="5"/>'],
        ['Transportation', 'পরিবহন', '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>'],
        ['Cold Storage', 'কোল্ড স্টোরেজ', '<path d="M2 12h20"/><path d="M12 2v20"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4 4 4"/>'],
        ['Veterinary Services', 'পশু চিকিৎসা', '<path d="M11 2v2"/><path d="M5 2v2"/><path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/><circle cx="20" cy="10" r="2"/>'],
      ];
    @endphp

    <div class="service-grid">
      @foreach($services as [$name, $bn, $icon])
      <div class="service-card">
        <div class="service-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icon !!}</svg>
        </div>
        <h4>{{ $name }}</h4>
        <div class="bn">{{ $bn }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>






@endsection @push('js') @endpush