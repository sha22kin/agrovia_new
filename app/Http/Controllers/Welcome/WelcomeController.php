<?php

namespace App\Http\Controllers\Welcome;

use Auth;
use Hash;
use Str;
use Artisan;
use Session;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\Post;
use App\Models\Media;
use App\Models\PostExtra;
use App\Models\User;
use App\Models\Attribute;
use App\Models\BusinessDocument;
use App\Models\MarketPrice;
use App\Models\Enquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WelcomeController extends Controller

{
    
	public function geo_filter($id){

      $datas=Country::where('parent_id',$id)->get();

      $geoData =View('geofilter',compact('datas'))->render();

       return Response()->json([
              'success' => true,
              'geoData' => $geoData,
            ]);
    }
    
    public function imageView(Request $r){
        if($r->imageUrl && is_numeric($r->weight) && is_numeric($r->height)){
          $image = imageManager()->make($r->imageUrl)->fit($r->weight,$r->height)->response();
        }else{
          $image = imageManager()->make('public/medies/noimage.jpg')->fit(200,200)->response(); 
        }
        return $image;
    }
    
    public function imageView2(Request $r,$template=null,$image=null){
        $weight=null;
        $height=null;
        
        if(is_numeric($r->w)){
          $weight=$r->w;  
        }
        if(is_numeric($r->h)){
          $height=$r->h;  
        }
        
        $filePath='public/medies/noimage.jpg';
        
        if($image){
            $file =Media::where('file_rename',$image)->select(['file_url'])->first();
            if($file){
                $filePath = $file->file_url;
            }
        }
        
        
        // if($template=='s-profile'){
            
        //     if($image && $image!='profile.png' && $file){
        //         $filePath = $file->file_url;
        //     }else{
        //         $filePath ='public/medies/profile.png';
        //     }
        // }
        
        $mImage =imageManager()->make($filePath);
        if($weight && $height){
            $mImage=$mImage->fit($weight,$height);
        }
        $mImage=$mImage->response();
        
        return $mImage;
    }
    public function siteMapXml(Request $r){
        
      $pages = Post::latest()->where('type',0)->where('status','active')->select(['slug','updated_at','status'])->limit(200)->get();
      $posts = Post::latest()->where('type',1)->where('status','active')->select(['slug','updated_at','status'])->limit(500)->get();
      $products = Post::latest()->where('type',2)->where('status','active')->select(['slug','updated_at','status'])->limit(300)->get();
      
      return response()->view('siteMap',compact('pages','posts','products'))->header('Content-Type', 'text/xml');
    }
    public function language($lang=null){
      if($lang){
          Session::put('lang',$lang);
      }else{
          Session::put('lang','en');
      }
      return redirect()->back();
    }

    public function index(Request $r){
        
    //   Artisan::call('cache:clear');
    //   Artisan::call('config:clear');
    //   Artisan::call('config:cache');
    //   Artisan::call('view:clear');
    //   Artisan::call('route:clear');
    //   Artisan::call('clear-compiled');
      
      // The landing page "Featured products" cards: every live ad (type=3), newest first.
      $latestServices =Post::live()
          ->with(['category','district','thana','user','galleryFiles'])
          ->withAvg(['postComments as avg_rating'],'rating')
          ->latest()
          ->get();

      foreach($latestServices as $service){
          $service->sellerVerified =$service->addedby_id
              && BusinessDocument::stateOf($service->addedby_id,BusinessDocument::TRADE_LICENSE) === 'verified';
      }
      
      $category =Attribute::latest()->where('type',0)->where('status','active')->get();
      
      $featuredCategory =Attribute::latest()->where('type',0)->where('status','active')->where('fetured',true)->limit(3)->get();

      $latestPosts =Post::where('type',1)
      ->where('status','active')
      ->whereDate('created_at','<=',Carbon::now())
      ->limit(3)
      ->get(['id','name','slug','addedby_id','created_at']);

      // "Marketplace categories": up to 8 top-level categories the admin marked for the home page.
      // Until any are marked, the first 8 active ones are shown so the section is never empty.
      $homeCtgQuery =fn () => Attribute::where('type',0)->where('status','active')->whereNull('parent_id')
          ->with('imageFile');
      $homeCategories =$homeCtgQuery()->where('fetured',true)
          ->orderByRaw('drag is null')->orderBy('drag')->orderBy('id')->limit(8)->get();
      if($homeCategories->isEmpty()){
          $homeCategories =$homeCtgQuery()->orderBy('id')->limit(8)->get();
      }

      // Hero search location picker: Bangladesh divisions with their districts.
      $heroDivisions =Country::where('type',2)
          ->whereIn('id',Country::where('type',3)->select('parent_id'))
          ->with(['subDatas' => fn ($q) => $q->where('type',3)->orderBy('name')])
          ->orderBy('name')->get(['id','name','bn_name']);

      $marketPrices =MarketPrice::board();
      $topDemand =Enquiry::topDemand();

    	return view(welcomeTheme().'index',compact('latestServices','latestPosts','category','featuredCategory','marketPrices','topDemand','homeCategories','heroDivisions'));
    }

    // Hero cards poll this to keep the price board and the demand card current.
    public function marketLive(){
      return response()->json([
        'prices' =>MarketPrice::board(),
        'demand' =>Enquiry::topDemand(),
      ]);
    }

    public function serviceCategory($slug){
      $category =Attribute::latest()->where('type',0)->where('slug',$slug)->first();
      if(!$category){
        return abort('404');
      }

      $services = Post::latest()
      ->where(function($qq)use($category){
          if($category->parent_id){
            $qq->where(function($sub) use($category){
                $sub->where('sub_category_id',$category->id)
                ->orWhereHas('serviceCtgs',function($link) use($category){
                    $link->where('reff_id',$category->id);
                });
            });
        }else{
            $qq->where('category_id',$category->id);
        }
        $qq->where('status','active');
      })
    //   ->select(['id','name','slug','addedby_id','created_at','short_description'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(12);

      return view(welcomeTheme().'services.categoryServices',compact('category','services'));
    }

    public function serviceView($slug){
      $service =Post::latest()->where('type',3)->where('slug',$slug)
      ->with(['category','district','thana','user','galleryFiles'])
      ->withAvg(['postComments as avg_rating'],'rating')
      ->first();
      if(!$service){
        return abort('404');
      }
      $service->sellerVerified =$service->addedby_id
          && BusinessDocument::stateOf($service->addedby_id,BusinessDocument::TRADE_LICENSE) === 'verified';

      // Similar products: live ads of the same category first, topped up with the newest other live ads.
      $similar =fn () => Post::live()->where('id','<>',$service->id)
        ->with(['galleryFiles','user:id,name','category:id,name','district:id,name','thana:id,name'])
        ->withAvg(['postComments as avg_rating'],'rating')
        ->latest()->latest('id');
      $rltServices =$similar()->when($service->category_id,fn ($q) => $q->where('category_id',$service->category_id))->take(8)->get();
      if($rltServices->count()<4){
        $rltServices =$rltServices->concat($similar()->whereNotIn('id',$rltServices->pluck('id'))->take(4-$rltServices->count())->get());
      }

      $verifiedSellers =BusinessDocument::where('type',BusinessDocument::TRADE_LICENSE)->where('status','verified')
        ->whereIn('user_id',$rltServices->pluck('addedby_id')->filter()->unique())->pluck('user_id')->flip();

      return view(welcomeTheme().'services.serviceView',compact('service','rltServices','verifiedSellers'));
    }

    public function blogCategory($slug){
      $category =Attribute::latest()->where('type',6)->where('slug',$slug)->first();
      if(!$category){
        return abort('404');
      }

      $posts = $category->activePosts()->latest()
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->paginate(12);

      return view(welcomeTheme().'blogs.categoryPosts',compact('category','posts'));
    }

    public function blogTag($slug){
      $tag =Attribute::latest()->where('type',7)->where('slug',$slug)->first();
      if(!$tag){
        return abort('404');
      }

      $posts = Post::whereHas('tagPosts',function($q) use($tag){
        $q->where('reff_id',$tag->id);
      })
      ->where(function($qq){
        $qq->where('status','active');
      })
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10);

      return view(welcomeTheme().'blogs.tagPosts',compact('tag','posts'));
    }

    public function blogAuthor($id,$slug){
      $author =User::find($id);
      if(!$author){
        return abort('404');
      }
      $posts =$author->posts()->latest()->where('type',1)->where('status','active')
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10);
      return view(welcomeTheme().'blogs.authorPosts',compact('author','posts'));

    }

    public function blogView($slug){
      $post =Post::where('type',1)->where('slug',$slug)->first();
      if(!$post){
        return abort('404');
      }
      // A draft (not active) can only be read by its author and by the admin.
      if($post->status!='active' && !(auth()->check() && (auth()->user()->isAdmin() || auth()->id()==$post->addedby_id))){
        return abort('404');
      }
      $relatedPosts =$post->relatedPosts()->limit(3)->select(['id','name','slug','short_description','addedby_id','created_at'])->get();
      $comments =$post->postComments()->where('status','active')->select(['id','name','content','created_at'])->paginate(10);
      return view(welcomeTheme().'blogs.blogView',compact('post','relatedPosts','comments'));

    }

    public function blogSearch(Request $r){
      $check = $r->validate([
          'search' => 'required|max:100',
      ]);
      
      $posts =Post::latest()->where('type',1)->where('status','active')
      ->where(function($q) use ($r) {
        if($r->search){
          $q->where('name','LIKE','%'.$r->search.'%');
        }

      })
      ->select(['id','name','slug','short_description','addedby_id','created_at'])
      ->whereDate('created_at','<=',date('Y-m-d'))
      ->paginate(10)->appends([
        'search'=>$r->search,
      ]);

      return view(welcomeTheme().'blogs.blogSearch',compact('posts','r'));

    }

    public function blogComments(Request $r,$slug){
      $post =Post::where('type',1)->where('slug',$slug)->first();
      if(!$post){
        return abort('404');
      }

      $check = $r->validate([
          'name' => 'required|max:100',
          'email' => 'required|max:100',
          'website' => 'required|max:100',
          'comment' => 'required|max:1000',
      ]);

      $comments =new Review();

      if(Auth::check()){
      $comments->addedby_id=Auth::id();
      }
      $comments->src_id=$post->id;
      $comments->type=1;
      $comments->name=$r->name;
      $comments->email=$r->email;
      $comments->website=$r->website;
      $comments->content=$r->comment;
      $comments->save();

      Session()->flash('success','Your Comments successfully Submitted.');

      return back();


    }


    public function pageView($slug){
    
      $page =Post::latest()->whereIn('type',[0,1])->where('slug',$slug)->first();

      if(!$page){
        return abort('404');
      }
      
      if($page->type==1){
          
          $post =$page;
          
          $relatedPosts =$post->relatedPosts()->limit(3)->select(['id','name','slug','short_description','addedby_id','created_at'])->get();
          $comments =$post->postComments()->where('status','active')->select(['id','name','content','created_at'])->paginate(10);
          
          return view(welcomeTheme().'blogs.blogView',compact('post','relatedPosts','comments'));
      }
      //If deferent Design or Condition Page Return by ID.

      //Font Home Page
      if($page->template=='Front Page'){
        return redirect()->route('index');
      }

      //Contact Us Page
      if($page->template=='Contact Us'){
        return view(welcomeTheme().'pages.contact',compact('page'));
      }

      //About Us Page
      if($page->template=='About Us'){
        return view(welcomeTheme().'pages.about',compact('page'));
      }
      
      //marketplace Page
      if($page->template=='Marketplace'){
        return $this->marketplacePage($page,request());
      }
      
      //RFQ Page
      if($page->template=='RFQ'){
        // Only enquiries the admin approved and that still take quotes.
        $rfqs =Enquiry::published()->with(['user:id,name','category:id,name'])->withCount('quotes')
          ->whereHas('user',fn ($q) => $q->notDeactivated())
          ->latest()->latest('id')->paginate(10);
        $rfqCategories =Attribute::where('type',0)->where('status','active')->whereNull('parent_id')->orderBy('name')->get(['id','name']);
        return view(welcomeTheme().'pages.RFQ',compact('page','rfqs','rfqCategories'));
      }
      
      //Experts Page
      if($page->template=='Experts'){
        return view(welcomeTheme().'pages.experts',compact('page'));
      }
      
      //Community Page
      if($page->template=='Community'){
        return view(welcomeTheme().'pages.community',compact('page'));
      }
      //Learn Page
      if($page->template=='Learn'){
        return view(welcomeTheme().'pages.learn',compact('page'));
      }
      
      //Career Page
      if($page->template=='Career'){
        return view(welcomeTheme().'pages.career',compact('page'));
      }
      
      //Stay Safe Page
      if($page->template=='Stay Safe'){
        return view(welcomeTheme().'pages.staySafe',compact('page'));
      }
      
      //Membership Page
      if($page->template=='Membership'){
        return view(welcomeTheme().'pages.membership',compact('page'));
      }
      
      //Sell Fast
      if($page->template=='Sell Fast'){
        return view(welcomeTheme().'pages.sellFast',compact('page'));
      }
      
      //Dashboard Page
      if($page->template=='Dashboard'){
        return view(welcomeTheme().'pages.customDashboard',compact('page'));
      }
      
      //FAQ Page
      if($page->template=='FAQ'){
        return view(welcomeTheme().'pages.faq',compact('page'));
      }
      
      //Clients Page
      if($page->template=='All Clients'){
        return view(welcomeTheme().'pages.clients',compact('page'));
      }
      
      //Sell Page
      if($page->template=='Sell Something'){
        return view(welcomeTheme().'pages.sellSomething',compact('page'));
      }
      
      //Banner Ads Page 
      if($page->template=='Banner Ads'){
        return view(welcomeTheme().'pages.bannerAds',compact('page'));
      }
      
      //Boost Ad Page 
      if($page->template=='Boost Ad'){
        return view(welcomeTheme().'pages.boostAd',compact('page'));
      }
      
      //Looking Page
      if($page->template=='Looking For Something'){
        return view(welcomeTheme().'pages.lookingForSomething',compact('page'));
      }

      //Latest Blog Page
      if($page->template=='Latest Blog'){
        $posts = Post::latest()->where('type',1)->where('status','active')
        ->select(['id','name','slug','short_description','addedby_id','created_at'])
        ->whereDate('created_at','<=',date('Y-m-d'))
        ->paginate(10);
        return view(welcomeTheme().'blogs.latestBlogs',compact('posts','page'));
      }

      //Latest Services Page
      if($page->template=='Latest Services'){
        $services = Post::latest()->where('type',3)->where('status','active')
        ->select(['id','name','slug','short_description','addedby_id','created_at'])
        ->whereDate('created_at','<=',date('Y-m-d'))
        ->paginate(12);
        return view(welcomeTheme().'services.latestServices',compact('services','page'));
      }

      return view(welcomeTheme().'pages.pageView',compact('page'));

    }

    public function contactMail(Request $r){
      //return $r;
      $check = $r->validate([
          'name' => 'required|max:100',
          'email' => 'required|max:100',
          'subject' => 'required|max:100',
          'message' => 'nullable|max:500',
      ]);

      if(general()->mail_status && general()->mail_from_address){
            //Mail Data
            $datas =array('r'=>$r);
            $template ='mails.ContactMail';
            $toEmail =general()->mail_from_address;
            $toName =general()->mail_from_name;
            $subject ='Contact Mail Form '.general()->title;
        
            sendMail($toEmail,$toName,$subject,$datas,$template);
        }

      Session()->flash('success','Your form send successfully done. We are response as soon as possible.');
      return back();
    }

    // Public marketplace: every seller ad the admin approved (live), with search, category,
    // division, verified seller filters and sorting. All filters are optional query values.
    private function marketplacePage($page,Request $r){

      // The home page search sends one "location" value: "division-ID" or "district-ID".
      if($r->filled('location')){
        [$level,$id] =array_pad(explode('-',(string) $r->location,2),2,null);
        $params =array_filter($r->except('location','division','district'),fn ($v) => $v !== null && $v !== '');
        if(in_array($level,['division','district']) && is_numeric($id)){
          $params[$level] =(int) $id;
        }
        return redirect(url()->current().($params ? '?'.http_build_query($params) : ''));
      }

      $categories =Attribute::where('type',0)->where('status','active')->whereNull('parent_id')
        ->orderBy('name')->get(['id','name','slug']);
      $adCounts =Post::live()->whereNotNull('category_id')->selectRaw('category_id, count(*) as total')
        ->groupBy('category_id')->pluck('total','category_id');
      $totalAds =$adCounts->sum();

      // Only divisions that have districts (the geo table also keeps other countries' divisions).
      $divisions =Country::where('type',2)
        ->whereIn('id',Country::where('type',3)->select('parent_id'))
        ->with(['subDatas' => fn ($q) => $q->where('type',3)->orderBy('name')])
        ->orderBy('name')->get(['id','name']);

      $category =$categories->firstWhere('id',(int) $r->category);
      // A district implies its division, so the sidebar shows where the visitor is.
      $district =$r->district ? Country::where('type',3)->find((int) $r->district,['id','name','bn_name','parent_id']) : null;
      $division =$divisions->firstWhere('id',$district ? $district->parent_id : (int) $r->division);
      $districts =$division ? $division->subDatas : collect();
      $keyword =trim((string) $r->search);
      $verified =$r->boolean('verified');
      $sorts =['newest'=>'Newest first','price_low'=>'Price: low to high','price_high'=>'Price: high to low'];
      $sort =array_key_exists($r->sort,$sorts)?$r->sort:'newest';

      $ads =Post::live()
        ->with(['galleryFiles','user:id,name','district:id,name,parent_id','thana:id,name'])
        ->withAvg(['postComments as avg_rating'],'rating')
        ->when($keyword!=='',fn ($q) => $q->keyword($keyword))
        ->when($category,fn ($q) => $q->where('category_id',$category->id))
        ->when($district,fn ($q) => $q->where('district_id',$district->id),
          fn ($q) => $q->when($division,fn ($q2) => $q2->whereIn('district_id',Country::where('type',3)->where('parent_id',$division->id)->select('id'))))
        ->when($verified,fn ($q) => $q->whereIn('addedby_id',BusinessDocument::where('type',BusinessDocument::TRADE_LICENSE)->where('status','verified')->select('user_id')))
        ->when($sort=='price_low',fn ($q) => $q->orderBy('price'),fn ($q) => $q->when($sort=='price_high',fn ($q2) => $q2->orderByDesc('price')))
        ->latest()->latest('id')
        ->paginate(12)->withQueryString();

      // Sellers of this page with a verified trade licence get the Verified badge.
      $verifiedSellers =BusinessDocument::where('type',BusinessDocument::TRADE_LICENSE)->where('status','verified')
        ->whereIn('user_id',$ads->pluck('addedby_id')->filter()->unique())->pluck('user_id')->flip();

      return view(welcomeTheme().'pages.marketplace',compact('page','categories','adCounts','totalAds','divisions',
        'category','division','district','districts','keyword','verified','sorts','sort','ads','verifiedSellers'));
    }

    public function search(Request $r){
      
      // Besides the keyword and category a search can be narrowed by district and price (saved searches use them).
      $posts =Post::latest()->matching($r->search,$r->category,$r->district,$r->min_price,$r->max_price)
      ->paginate(12)->appends([
        'search'=>$r->search,
        'category'=>$r->category,
        'district'=>$r->district,
        'min_price'=>$r->min_price,
        'max_price'=>$r->max_price,
      ]);

      return view(welcomeTheme().'search',compact('posts','r'));

    }

    public function subscribe(Request $r){

      if(filter_var($r->email, FILTER_VALIDATE_EMAIL)){
        $subscribe =PostExtra::latest()->where('type',1)->where('name',$r->email)->first();

        if(!$subscribe){
          
            $subscribe =new PostExtra();
            $subscribe->type=1;
            $subscribe->name=$r->email;
            $subscribe->save();
          $status=true;
          $message ='<p style="color: #009688;"><span>Success:</span> You Are Successfully Subsribe.</p>';
        }else{
          $status=true;
          $message ='<p style="color: #ffc107;"><span>Note:</span> You Are Already Subsribe.Thank You.</p>';
        }


      }else{
        $status=false;
        $message ='<p style="color: #ff5722;"><span>Error:</span> Email Are Not validated</p>';
      }

      if(request()->ajax()){
        
        return Response()->json([
                'success' => $status,
                'message' => $message,
              ]);
      }

      Session()->flash($status?'success':'error',$message);
      return redirect()->back();

    }





}
