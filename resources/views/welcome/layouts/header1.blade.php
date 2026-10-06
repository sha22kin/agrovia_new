<!-- header part start -->
<header>
    <div class="topHeader">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="conInfoLink">
                        <ul>
                            <li><a href="tel:{{general()->mobile}}"><i class="fa fa-phone"></i> {{general()->mobile}}</a></li>
                            <li><a href="mailto:{{general()->email}}"><i class="fa fa-envelope"></i> {{general()->email}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="socInfoLink">
                        <ul>
                            @if(general()->facebook_link)
                            <li><a href="{{general()->facebook_link}}"><i class="fa fa-facebook"></i></a></li>
                            @endif
                            @if(general()->twitter_link)
                            <li><a href="{{general()->twitter_link}}"><i class="fa fa-twitter"></i></a></li>
                            @endif
                            @if(general()->instagram_link)
                            <li><a href="{{general()->instagram_link}}"><i class="fa fa-instagram"></i></a></li>
                            @endif
                            @if(general()->linkedin_link)
                            <li><a href="{{general()->linkedin_link}}"><i class="fa fa-linkedin"></i></a></li>
                            @endif
                            @if(general()->youtube_link)
                            <li><a href="{{general()->youtube_link}}"><i class="fa fa-youtube-play"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="headerMainPart">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="headerLogo">
                        <a href="{{route('index')}}"><img src="{{asset(general()->logo())}}" alt="{{general()->title}}"></a>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="searchBox">
                        <div class="input-group">
                          <input type="text" class="form-control" placeholder="Search Products" />
                          <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="postBtn">
                        <a href="{{route('customer.listingsAction','create')}}">Post a Free Ad</a>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="userDiv">
                        @if(Auth::check())
                        <a href="{{route('customer.dashboard')}}">
                        @else
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#AuthModal">
                        @endif
                            <img src="{{asset('public/welcome/images/user.png')}}" alt="FSBS" />
                        </a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="languagePart">
                        <div class="gtranslate_wrapper"></div>
                        <script>window.gtranslateSettings = {"default_language":"en","native_language_names":true,"detect_browser_language":true,"languages":["en","bn"],"wrapper_selector":".gtranslate_wrapper"}</script>
                        <script src="https://cdn.gtranslate.net/widgets/latest/dropdown.js" defer></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="hamburger-menu">
        <div class="bar"></div> 
    </div>
    
    <nav class="mobile-menu">
        @if($menu = menu('Footer Two'))
        <ul>
            @foreach($menu->subMenus as $menu)
            <li><a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a></li>
            @endforeach        
        </ul>
        @endif
    </nav>
    
    <div class="headerMenuPart">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="ctgAll">
                        <h3>All Category</h3>
                        @if($menu = menu('Footer Two'))
                        <ul class="allMenuCtg">
                            @foreach($menu->subMenus as $menu)
                            <li><a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="navDiv">
                        <ul>
                            <li>
                                <a href="{{route('index')}}">Home</a>
                            </li>
                            @if(menu('Header Menus'))
                            @foreach(menu('Header Menus')->subMenus as $menu)
                            @if($menu->subMenus->count() > 0)
                            <li class="dropdown">
                                <a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a>
                                <ul class="dropdown-content">
                                    @foreach($menu->subMenus as $menu)
                                    <li>
                                        <a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @else
                            <li>
                                <a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a>
                            </li>
                            @endif
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
<!-- header part end -->