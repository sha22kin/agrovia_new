<!-- footer part start -->
<footer>
    <div class="footerPartMain">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="footList">
                        @if($menu = menu('Footer Three'))
                        <h4>{{$menu->name}}</h4>
                        <ul>
                            @foreach($menu->subMenus as $menu)
                            <li><a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="footList">
                        @if($menu = menu('Footer Four'))
                        <h4>{{$menu->name}}</h4>
                        <ul>
                            @foreach($menu->subMenus as $menu)
                            <li><a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="footList">
                        @if($menu = menu('Footer Five'))
                        <h4>{{$menu->name}}</h4>
                        <ul>
                            @foreach($menu->subMenus as $menu)
                            <li><a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="footList">
                        @if($menu = menu('Footer Six'))
                        <h4>{{$menu->name}}</h4>
                        <ul>
                            @foreach($menu->subMenus as $menu)
                            <li><a href="{{asset($menu->menuLink())}}">{{$menu->menuName()}}</a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                {{--<div class="col-md-3">
                    <div class="footList">
                        <h4>Download our app</h4>
                        <img src="{{asset('public/welcome/images/googlplay.jpg')}}" alt="FSBS" />
                        <img src="{{asset('public/welcome/images/appplay.jpg')}}" alt="FSBS" />
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
    <div class="footerBottomPart">
        <div class="container">
            <p>© Factory Surplus Buy Sell FSBS 2024, All Rights Reserved.</p>
        </div>
    </div>
</footer>
<!-- footer part end -->
