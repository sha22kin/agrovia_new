@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle($service->seo_title?:$service->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$service->seo_title?:$service->name.' | '.general()->title}}" />
<meta name="description" property="og:description" content="{!!$service->seo_desc?:$service->name.' '.general()->meta_description!!}" />
<meta name="keywords" content="{{$service->seo_keyword?:$service->name.' '.general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($service->fiImage())}}" />
<meta name="url" property="og:url" content="{{route('serviceView',$service->slug?:Str::slug($service->name))}}" />
<link rel="canonical" href="{{route('serviceView',$service->slug?:Str::slug($service->name))}}">
@endsection
@push('css')
<style>
    /* newStyle.css gives every .row a 24px gap; here Bootstrap's gutters do the spacing (see .agv-market). */
    .ad-wrap .row, .ad-similar .row { gap: 0; }

    .ad-breadcrumb{background:var(--white);border-bottom:1px solid var(--border);padding:14px 0;font-size:13px;color:var(--muted);overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}
    .ad-breadcrumb a{color:var(--muted);}
    .ad-breadcrumb a:hover{color:var(--green-700);}
    .ad-breadcrumb .current{color:var(--text);font-weight:600;}
    .ad-breadcrumb span.sep{margin:0 6px;color:#c7d2c8;}

    .ad-wrap{padding:28px 0 40px;}
    .ad-box{background:var(--white);border:1px solid var(--border);border-radius:16px;}

    /* ---------- Gallery (left) ---------- */
    .ad-gallery{padding:12px;position:sticky;top:20px;}
    .ad-hero{position:relative;aspect-ratio:1/1;max-height:560px;width:100%;border-radius:12px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:linear-gradient(135deg,#eef7ef,#e1f0e4);cursor:zoom-in;}
    .ad-hero::before{content:"";position:absolute;inset:-20px;background:var(--photo,none) center/cover no-repeat;filter:blur(22px) saturate(1.1);opacity:.45;}
    .ad-hero.no-photo{cursor:default;}
    .ad-hero.no-photo::before{display:none;}
    .ad-hero img{position:relative;width:100%;height:100%;object-fit:contain;transition:transform .15s ease-out;will-change:transform;}
    .ad-hero.zooming img{transform:scale(2.2);}
    .ad-hero-icon{position:relative;font-size:72px;color:var(--green-700);display:none;}
    .ad-hero.no-photo .ad-hero-icon{display:block;}
    .ad-hero-nav{position:absolute;top:50%;transform:translateY(-50%);z-index:2;width:40px;height:40px;border-radius:50%;border:0;background:rgba(255,255,255,.95);color:var(--text);box-shadow:0 4px 12px rgba(0,0,0,.15);display:flex;align-items:center;justify-content:center;font-size:20px;cursor:pointer;transition:background .15s ease,color .15s ease;}
    .ad-hero-nav.prev{left:12px;} .ad-hero-nav.next{right:12px;}
    .ad-hero-nav:hover{background:var(--green-700);color:#fff;}
    .ad-hero-badge{position:absolute;z-index:2;font-size:12px;font-weight:600;border-radius:20px;padding:5px 11px;pointer-events:none;}
    .ad-hero-badge.count{right:12px;bottom:12px;background:rgba(15,30,20,.65);color:#fff;}
    .ad-hero-badge.hint{left:12px;bottom:12px;background:rgba(255,255,255,.92);color:var(--text);box-shadow:0 2px 6px rgba(0,0,0,.1);}
    .ad-hero.zooming .ad-hero-badge,.ad-hero.zooming .ad-hero-nav{opacity:0;}
    .ad-thumbs{display:flex;gap:8px;margin-top:10px;overflow-x:auto;padding-bottom:2px;}
    .ad-thumbs button{flex:0 0 74px;width:74px;height:74px;padding:0;border-radius:10px;overflow:hidden;border:2px solid var(--border);background:#eef5ef;opacity:.7;cursor:pointer;transition:opacity .2s ease,border-color .2s ease;}
    .ad-thumbs button img{width:100%;height:100%;object-fit:cover;display:block;}
    .ad-thumbs button.is-active,.ad-thumbs button:hover{opacity:1;border-color:var(--green-600);}

    /* ---------- Product info (right) ---------- */
    .ad-info{padding:24px;}
    .ad-chip{display:inline-block;font-size:12px;font-weight:600;color:var(--green-800);background:var(--green-50);border:1px solid #cfe4d4;border-radius:20px;padding:3px 11px;margin-bottom:10px;text-decoration:none;}
    .ad-title{font-size:26px;font-weight:800;color:var(--text);line-height:1.3;margin:0;overflow-wrap:anywhere;}
    .ad-meta-row{display:flex;flex-wrap:wrap;gap:6px 18px;color:var(--muted);font-size:13px;margin-top:10px;}
    .ad-meta-row span{display:inline-flex;align-items:center;gap:6px;}
    .ad-meta-row .fa{color:var(--green-600);}
    .ad-meta-row .fa-star{color:#f5a623;}

    .ad-price-box{display:flex;align-items:flex-end;flex-wrap:wrap;gap:10px 14px;margin:18px 0;padding:16px 0;border-top:1px dashed var(--border);border-bottom:1px dashed var(--border);}
    .ad-price{font-size:34px;font-weight:800;color:var(--green-700);line-height:1;}
    .ad-negotiable{font-size:12.5px;font-weight:600;color:#b4700c;background:#fff4e5;padding:5px 12px;border-radius:20px;}

    .ad-facts{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;}
    .ad-fact{display:flex;align-items:center;gap:10px;border:1px solid var(--border);border-radius:12px;padding:10px 12px;min-width:0;}
    .ad-fact .fa{flex:0 0 32px;height:32px;border-radius:8px;background:var(--green-50);color:var(--green-700);display:flex;align-items:center;justify-content:center;font-size:14px;}
    .ad-fact small{display:block;font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.03em;}
    .ad-fact b{display:block;font-size:13.5px;color:var(--text);overflow-wrap:anywhere;}

    .ad-buy{margin-top:18px;display:grid;grid-template-columns:1fr 1fr;gap:10px;}
    .ad-buy .full{grid-column:1/-1;}
    .ad-phone-box{border:1px solid var(--border);border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;}
    a.ad-phone-box{background:var(--green-50);border-color:#cfe4d4;}
    a.ad-phone-box:hover{border-color:var(--green-600);color:inherit;}
    .ad-phone-box i{color:var(--green-700);font-size:20px;width:22px;text-align:center;}
    .ad-phone-box b{display:block;font-size:17px;color:var(--text);letter-spacing:.02em;}
    .ad-phone-box small{display:block;color:var(--muted);font-size:11.5px;margin-top:1px;}
    .ad-action-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:12px;border-radius:10px;font-weight:600;font-size:14px;border:1px solid var(--border);color:var(--text);background:#fff;transition:all .2s ease;cursor:pointer;text-decoration:none;}
    .ad-action-btn:hover{border-color:var(--green-600);color:var(--green-700);}
    .ad-action-btn.primary{background:var(--green-700);border-color:var(--green-700);color:#fff;}
    .ad-action-btn.primary:hover{background:var(--green-900);color:#fff;}
    .ad-action-btn.active{background:var(--green-50);border-color:#cfe4d4;color:var(--green-700);}

    .ad-share{display:flex;align-items:center;gap:8px;margin-top:16px;font-size:13px;color:var(--muted);}
    .ad-share a{width:34px;height:34px;border-radius:50%;background:var(--green-50);color:var(--green-700);display:flex;align-items:center;justify-content:center;transition:background .2s ease,transform .2s ease,color .2s ease;}
    .ad-share a:hover{background:var(--green-600);color:#fff;transform:translateY(-2px);}

    /* ---------- Below: description + seller ---------- */
    .ad-section-title{font-size:17px;font-weight:700;color:var(--text);margin:0 0 12px;}
    .ad-description{padding:22px;line-height:1.8;color:#3d4a41;font-size:14.5px;overflow-wrap:anywhere;height:100%;}
    .ad-seller-card{padding:22px;height:100%;}
    .ad-seller-head{display:flex;align-items:center;gap:12px;margin-bottom:14px;}
    .ad-seller-avatar{flex:0 0 52px;height:52px;border-radius:50%;background:var(--green-100);color:var(--green-800);font-weight:800;font-size:20px;display:flex;align-items:center;justify-content:center;}
    .ad-seller-name{font-size:16px;font-weight:700;color:var(--text);overflow-wrap:anywhere;}
    .ad-seller-tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:4px;}
    .ad-seller-tags span{font-size:11px;font-weight:600;border-radius:20px;padding:2px 9px;background:#eef2ff;color:#4148b0;}
    .ad-seller-tags span.verified{background:#e6f4e9;color:var(--green-800);}
    .ad-seller-line{display:flex;gap:8px;font-size:13px;color:var(--muted);padding:7px 0;border-bottom:1px dashed var(--border);}
    .ad-seller-line .fa{width:16px;color:var(--green-600);margin-top:3px;}
    .ad-seller-line b{color:var(--text);font-weight:600;}
    .ad-safety-box{margin-top:14px;background:#fffaf0;border:1px solid #f6e3bd;border-radius:12px;padding:14px 16px;font-size:12.5px;color:#6b5a3a;line-height:1.6;}
    .ad-safety-box b{display:flex;align-items:center;gap:6px;color:#8a5a00;margin-bottom:6px;font-size:13px;}

    .ad-similar{padding:8px 0 60px;}
    .ad-similar-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:16px;}
    .ad-similar-head a{font-size:13.5px;font-weight:600;color:var(--green-700);}

    /* ---------- Full screen viewer with zoom ---------- */
    .ad-lightbox{position:fixed;inset:0;z-index:2000;background:rgba(10,18,12,.97);display:none;flex-direction:column;}
    .ad-lightbox.open{display:flex;}
    .ad-lb-top{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;color:#fff;font-size:14px;}
    .ad-lb-tools{display:flex;gap:8px;}
    .ad-lb-tools button,.ad-lb-nav{width:42px;height:42px;border-radius:50%;border:0;background:rgba(255,255,255,.12);color:#fff;font-size:18px;display:flex;align-items:center;justify-content:center;cursor:pointer;}
    .ad-lb-tools button:hover,.ad-lb-nav:hover{background:rgba(255,255,255,.25);}
    .ad-lb-stage{position:relative;flex:1;overflow:hidden;display:flex;align-items:center;justify-content:center;touch-action:none;cursor:zoom-in;}
    .ad-lb-stage.zoomed{cursor:grab;}
    .ad-lb-stage img{max-width:100%;max-height:100%;object-fit:contain;transition:transform .2s ease-out;user-select:none;-webkit-user-drag:none;}
    .ad-lb-stage.dragging img{transition:none;}
    .ad-lb-nav{position:absolute;top:50%;transform:translateY(-50%);z-index:2;}
    .ad-lb-nav.prev{left:14px;} .ad-lb-nav.next{right:14px;}
    .ad-lb-hint{text-align:center;color:rgba(255,255,255,.6);font-size:12px;padding:8px 10px 14px;}

    @media (max-width:991.98px){
        .ad-gallery{position:static;}
        .ad-hero{max-height:460px;}
    }
    @media (max-width:767.98px){
        .ad-info,.ad-description,.ad-seller-card{padding:16px;}
        .ad-title{font-size:21px;}
        .ad-price{font-size:28px;}
        .ad-thumbs button{flex-basis:60px;width:60px;height:60px;}
        .ad-hero-badge.hint{display:none;}
    }
    @media (max-width:359.98px){
        .ad-facts,.ad-buy{grid-template-columns:1fr;}
    }
</style>
@endpush
@section('contents')
@php
    $photos = $service->galleryFiles;
    $first = $photos->first();
    $sellerName = $service->user ? ($service->user->company_name ?: $service->user->name) : 'Seller';
    $pageUrl = route('serviceView', $service->slug ?: Str::slug($service->name));
@endphp

<div class="ad-breadcrumb">
    <div class="container">
        <a href="{{route('index')}}">Home</a>
        <span class="sep">/</span><a href="{{route('pageView','marketplace')}}">Marketplace</a>
        @if($service->category)
        <span class="sep">/</span><a href="{{route('pageView',['marketplace','category'=>$service->category->id])}}">{{$service->category->name}}</a>
        @endif
        <span class="sep">/</span><span class="current">{{Str::limit($service->name,50)}}</span>
    </div>
</div>

<div class="ad-wrap">
    <div class="container">
        <div class="row g-4">
            {{-- Photos --}}
            <div class="col-lg-6">
                <div class="ad-box ad-gallery">
                    <div class="ad-hero {{$first ? '' : 'no-photo'}}" id="adHero" @if($first) style="--photo:url('{{asset($first->image())}}')" @endif>
                        @if($first)
                        <img id="adHeroImg" src="{{asset($first->image())}}" alt="{{$service->name}}" onerror="this.closest('.ad-hero').classList.add('no-photo');this.style.display='none';">
                        <span class="ad-hero-badge hint"><i class="fa fa-search-plus"></i> Hover to zoom · click for full screen</span>
                        @endif
                        <i class="fa fa-leaf ad-hero-icon"></i>
                        @if($photos->count() > 1)
                        <button type="button" class="ad-hero-nav prev" data-step="-1" aria-label="Previous photo"><i class="fa fa-angle-left"></i></button>
                        <button type="button" class="ad-hero-nav next" data-step="1" aria-label="Next photo"><i class="fa fa-angle-right"></i></button>
                        <span class="ad-hero-badge count"><i class="fa fa-camera"></i> <span id="adHeroIndex">1</span> / {{$photos->count()}}</span>
                        @endif
                    </div>
                    @if($photos->count() > 1)
                    <div class="ad-thumbs">
                        @foreach($photos as $i=>$file)
                        <button type="button" class="{{$i==0?'is-active':''}}" data-thumb="{{asset($file->image())}}" aria-label="Photo {{$i+1}}">
                            <img src="{{asset($file->image())}}" alt="" loading="lazy" onerror="this.style.visibility='hidden';">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Product info and contact --}}
            <div class="col-lg-6">
                <div class="ad-box ad-info">
                    @if($service->category)<a href="{{route('pageView',['marketplace','category'=>$service->category->id])}}" class="ad-chip">{{$service->category->name}}</a>@endif
                    <h1 class="ad-title">{{$service->name}}</h1>
                    <div class="ad-meta-row">
                        <span><i class="fa fa-calendar"></i> Posted {{$service->created_at->format('d M, Y')}}</span>
                        @if($service->area())<span><i class="fa fa-map-marker"></i> {{$service->area()}}</span>@endif
                        @if($service->avg_rating)<span><i class="fa fa-star"></i> {{number_format($service->avg_rating,1)}}</span>@endif
                    </div>

                    <div class="ad-price-box">
                        <div class="ad-price">৳{{number_format((float) $service->price)}}</div>
                        @if($service->negotiable)<div class="ad-negotiable"><i class="fa fa-handshake-o"></i> Negotiable</div>@endif
                    </div>

                    <div class="ad-facts">
                        <div class="ad-fact"><i class="fa fa-tag"></i><div><small>Category</small><b>{{$service->category?->name ?: '—'}}</b></div></div>
                        <div class="ad-fact"><i class="fa fa-map-marker"></i><div><small>Location</small><b>{{$service->district?->name ?: '—'}}</b></div></div>
                        <div class="ad-fact"><i class="fa fa-clock-o"></i><div><small>Posted</small><b>{{$service->created_at->diffForHumans()}}</b></div></div>
                        <div class="ad-fact"><i class="fa fa-hashtag"></i><div><small>Ad ID</small><b>{{$service->id}}</b></div></div>
                    </div>

                    <div class="ad-buy">
                        @if($service->hide_phone || !$service->phone_number)
                        <div class="ad-phone-box full">
                            <i class="fa fa-phone"></i>
                            <div><small>Phone number is hidden by the seller</small></div>
                        </div>
                        @else
                        <a href="tel:{{$service->phone_number}}" class="ad-phone-box full">
                            <i class="fa fa-phone"></i>
                            <div><b>{{$service->phone_number}}</b><small>Tap to call the seller</small></div>
                        </a>
                        @endif

                        @guest
                        <a href="{{route('login')}}" class="ad-action-btn primary"><i class="fa fa-comments-o"></i> Chat with seller</a>
                        <a href="{{route('login')}}" class="ad-action-btn"><i class="fa fa-heart-o"></i> Save</a>
                        @else
                        <a href="{{auth()->user()->isBuyer() && $service->addedby_id ? route('buyer.messages',['to'=>$service->addedby_id,'ad'=>$service->id]) : '#'}}" class="ad-action-btn primary {{auth()->user()->isBuyer() ? '' : 'full'}}"><i class="fa fa-comments-o"></i> Chat with seller</a>
                        @if(auth()->user()->isBuyer())
                        <form id="wishlistForm" action="{{route('buyer.wishlistToggle',$service->id)}}" method="POST" class="d-none">@csrf</form>
                        @php $saved = \App\Models\Wishlist::has(auth()->id(),$service->id); @endphp
                        <a href="#" class="ad-action-btn {{$saved?'active':''}}" onclick="document.getElementById('wishlistForm').submit();return false;">
                            <i class="fa {{$saved?'fa-heart':'fa-heart-o'}}"></i> {{$saved?'Saved':'Save'}}
                        </a>
                        @endif
                        @endguest
                    </div>

                    <div class="ad-share">
                        Share:
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{urlencode($pageUrl)}}" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="fa fa-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{urlencode($pageUrl)}}&text={{urlencode($service->name)}}" target="_blank" rel="noopener" aria-label="Share on Twitter"><i class="fa fa-twitter"></i></a>
                        <a href="https://wa.me/?text={{urlencode($service->name.' '.$pageUrl)}}" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="fa fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="col-lg-8">
                <div class="ad-box ad-description">
                    <h2 class="ad-section-title">Description</h2>
                    {!! nl2br(e($service->description ?: 'No description provided.')) !!}
                </div>
            </div>

            {{-- Seller --}}
            <div class="col-lg-4">
                <div class="ad-box ad-seller-card">
                    <h2 class="ad-section-title">Seller information</h2>
                    <div class="ad-seller-head">
                        <div class="ad-seller-avatar">{{Str::upper(Str::substr($sellerName,0,1))}}</div>
                        <div style="min-width:0;">
                            <div class="ad-seller-name">{{$sellerName}}</div>
                            <div class="ad-seller-tags">
                                <span>{{$service->user && $service->user->company_name ? 'Company' : 'Farmer'}}</span>
                                @if($service->sellerVerified)<span class="verified"><i class="fa fa-check-circle"></i> Verified</span>@endif
                            </div>
                        </div>
                    </div>
                    @if($service->area())
                    <div class="ad-seller-line"><i class="fa fa-map-marker"></i><span>Location: <b>{{$service->area()}}</b></span></div>
                    @endif
                    @if($service->user?->created_at)
                    <div class="ad-seller-line"><i class="fa fa-user"></i><span>Member since <b>{{$service->user->created_at->format('M Y')}}</b></span></div>
                    @endif

                    <div class="ad-safety-box">
                        <b><i class="fa fa-shield"></i> Stay alert: avoid online scams</b>
                        Never share card details or OTPs, and always verify items in person before payment. {{general()->title}} does not offer a delivery service — stay vigilant.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($rltServices->count())
<div class="ad-similar">
    <div class="container">
        <div class="ad-similar-head">
            <h2 class="ad-section-title mb-0" style="font-size:20px;">Similar products</h2>
            <a href="{{route('pageView',array_filter(['marketplace','category'=>$service->category_id]))}}">See all <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="row g-2 g-sm-3">
            @foreach($rltServices as $r)
            <div class="col-xl-3 col-md-4 col-6">
                @include(welcomeTheme().'services.includes.adCard', [
                    'ad' => $r,
                    'categoryName' => $r->category?->name,
                    'verified' => isset($verifiedSellers[$r->addedby_id]),
                ])
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@if($first)
{{-- Full screen photo viewer: click / tap to zoom, drag to move, arrows or swipe for the next photo. --}}
<div class="ad-lightbox" id="adLightbox" role="dialog" aria-modal="true" aria-label="Photo viewer">
    <div class="ad-lb-top">
        <span><span id="adLbIndex">1</span> / {{max(1,$photos->count())}}</span>
        <div class="ad-lb-tools">
            <button type="button" data-lb="out" aria-label="Zoom out"><i class="fa fa-search-minus"></i></button>
            <button type="button" data-lb="in" aria-label="Zoom in"><i class="fa fa-search-plus"></i></button>
            <button type="button" data-lb="close" aria-label="Close"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="ad-lb-stage" id="adLbStage">
        <img id="adLbImg" src="" alt="{{$service->name}}">
        @if($photos->count() > 1)
        <button type="button" class="ad-lb-nav prev" data-lb="prev" aria-label="Previous photo"><i class="fa fa-angle-left"></i></button>
        <button type="button" class="ad-lb-nav next" data-lb="next" aria-label="Next photo"><i class="fa fa-angle-right"></i></button>
        @endif
    </div>
    <div class="ad-lb-hint">Click or double tap to zoom · drag to move · Esc to close</div>
</div>
@endif

@endsection
@push('js')
<script>
(function(){
    var photos = Array.prototype.map.call(document.querySelectorAll('[data-thumb]'), function(b){ return b.dataset.thumb; });
    var hero = document.getElementById('adHero');
    var heroImg = document.getElementById('adHeroImg');
    if(!hero || !heroImg) return;
    if(!photos.length) photos = [heroImg.getAttribute('src')];

    var thumbs = document.querySelectorAll('[data-thumb]');
    var heroIndex = document.getElementById('adHeroIndex');
    var current = 0;

    // ----- Main photo -----
    function show(i){
        current = (i + photos.length) % photos.length;
        hero.classList.remove('no-photo');
        heroImg.style.display = '';
        heroImg.src = photos[current];
        hero.style.setProperty('--photo', 'url("' + photos[current] + '")');
        thumbs.forEach(function(x, n){ x.classList.toggle('is-active', n === current); });
        if(heroIndex) heroIndex.textContent = current + 1;
    }
    thumbs.forEach(function(t, n){ t.addEventListener('click', function(){ show(n); }); });
    hero.querySelectorAll('.ad-hero-nav').forEach(function(btn){
        btn.addEventListener('click', function(e){ e.stopPropagation(); show(current + parseInt(btn.dataset.step, 10)); });
    });

    // Hover zoom (mouse only): the photo is enlarged around the cursor.
    var canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if(canHover){
        hero.addEventListener('mousemove', function(e){
            if(hero.classList.contains('no-photo') || e.target.closest('.ad-hero-nav')){ hero.classList.remove('zooming'); return; }
            var r = hero.getBoundingClientRect();
            heroImg.style.transformOrigin = ((e.clientX - r.left) / r.width * 100) + '% ' + ((e.clientY - r.top) / r.height * 100) + '%';
            hero.classList.add('zooming');
        });
        hero.addEventListener('mouseleave', function(){ hero.classList.remove('zooming'); });
    }

    // ----- Full screen viewer -----
    var box = document.getElementById('adLightbox');
    var stage = document.getElementById('adLbStage');
    var img = document.getElementById('adLbImg');
    var lbIndex = document.getElementById('adLbIndex');
    var lbCurrent = 0, scale = 1, x = 0, y = 0;

    function apply(){
        img.style.transform = 'translate(' + x + 'px,' + y + 'px) scale(' + scale + ')';
        stage.classList.toggle('zoomed', scale > 1);
    }
    function zoomTo(s){
        scale = Math.min(4, Math.max(1, s));
        if(scale === 1){ x = 0; y = 0; }
        apply();
    }
    function lbShow(i){
        lbCurrent = (i + photos.length) % photos.length;
        img.src = photos[lbCurrent];
        lbIndex.textContent = lbCurrent + 1;
        zoomTo(1);
    }
    function open(i){
        box.classList.add('open');
        document.body.style.overflow = 'hidden';
        lbShow(i);
    }
    function close(){
        box.classList.remove('open');
        document.body.style.overflow = '';
        show(lbCurrent);
    }

    hero.addEventListener('click', function(e){
        if(hero.classList.contains('no-photo') || e.target.closest('.ad-hero-nav')) return;
        open(current);
    });
    box.addEventListener('click', function(e){
        var b = e.target.closest('[data-lb]');
        if(!b) return;
        e.stopPropagation();
        var a = b.dataset.lb;
        if(a === 'close') close();
        else if(a === 'in') zoomTo(scale + 0.75);
        else if(a === 'out') zoomTo(scale - 0.75);
        else if(a === 'prev') lbShow(lbCurrent - 1);
        else if(a === 'next') lbShow(lbCurrent + 1);
    });
    document.addEventListener('keydown', function(e){
        if(!box.classList.contains('open')) return;
        if(e.key === 'Escape') close();
        else if(e.key === 'ArrowLeft') lbShow(lbCurrent - 1);
        else if(e.key === 'ArrowRight') lbShow(lbCurrent + 1);
        else if(e.key === '+' || e.key === '=') zoomTo(scale + 0.75);
        else if(e.key === '-') zoomTo(scale - 0.75);
    });
    stage.addEventListener('wheel', function(e){
        e.preventDefault();
        zoomTo(scale + (e.deltaY < 0 ? 0.3 : -0.3));
    }, {passive: false});

    // Click (or double tap) toggles 2.5x zoom; drag (or swipe) moves the photo, or changes photo when not zoomed.
    var down = null, moved = false, lastTap = 0;
    stage.addEventListener('pointerdown', function(e){
        if(e.target.closest('[data-lb]')) return;
        down = {px: e.clientX, py: e.clientY, x: x, y: y};
        moved = false;
        stage.setPointerCapture(e.pointerId);
        stage.classList.add('dragging');
    });
    stage.addEventListener('pointermove', function(e){
        if(!down) return;
        var dx = e.clientX - down.px, dy = e.clientY - down.py;
        if(Math.abs(dx) > 5 || Math.abs(dy) > 5) moved = true;
        if(scale > 1){ x = down.x + dx; y = down.y + dy; apply(); }
    });
    stage.addEventListener('pointerup', function(e){
        if(!down) return;
        var dx = e.clientX - down.px;
        stage.classList.remove('dragging');
        if(!moved){
            var touch = e.pointerType !== 'mouse', now = Date.now();
            if(!touch || now - lastTap < 300){
                if(scale > 1){ zoomTo(1); }
                else {
                    // zoom in towards the clicked point
                    var r = img.getBoundingClientRect();
                    scale = 2.5;
                    x = (r.left + r.width / 2 - e.clientX) * 1.5;
                    y = (r.top + r.height / 2 - e.clientY) * 1.5;
                    apply();
                }
                lastTap = 0;
            } else {
                lastTap = now;
            }
        } else if(scale === 1 && Math.abs(dx) > 50 && photos.length > 1){
            lbShow(lbCurrent + (dx < 0 ? 1 : -1));
        }
        down = null;
    });
})();
</script>
@endpush
