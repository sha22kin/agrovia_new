<div class="blog-sidebar">
	<div class="blogSearch">
		<h2 class="widget-title">Search Blog </h2>
		<form action="{{route('blogSearch')}}">
			<div class="input-group">
				<input type="text" name="search" value="{{request()->search}}" class="form-control" placeholder="Search" title="Search for." />
				<button type="submit" class="btn">
					<i class="fa fa-search"></i>
				</button>
			</div>
		</form>
	</div>
	<hr>
	{{--<div class="widget_categories">
		<h2 class="widget-title">Categories </h2>
		<ul>
		@foreach(App\Models\Attribute::where('type',6)->where('status','active')->where('parent_id',null)->orderBy('name')->limit(5)->get() as $ctg)
		<li><a href="{{route('blogCategory',$ctg->slug?:'no-title')}}">{{$ctg->name}} ({{$ctg->activePosts()->count()}})</a></li>
		@endforeach
		</ul>
	</div>
	<hr>--}}
	<div class="widget_categories">
		<h2 class="widget-title">Recent Posts </h2>
		@foreach(App\Models\Post::where('type',1)->where('status','active')->latest()->limit(5)->get() as $lPost)
		
		<div class="card mb-3" style="max-width: 540px;">
			<div class="row g-0 m-0">
				<div class="col-md-4 p-0">
				<img src="{{asset($lPost->image())}}" class="img-fluid rounded-start" alt="{{$lPost->name}}">
				</div>
				<div class="col-md-8 p-0">
				<div class="card-body" style="padding:5px 10px;">
					<a href="{{route('blogView',$lPost->slug?:'no-title')}}">{{$lPost->name}}</a>
					<p style="margin:0;"><small>{{$lPost->created_at->format('d-m-Y')}}</small></p>
				</div>
				</div>
			</div>
		</div>

		@endforeach

	</div>
	{{--
	<div class="widget widget_meta">
		<h2 class="widget-title">Meta Tag</h2>
		<ul>
    		@foreach(App\Models\Attribute::where('type',7)->where('status','active')->where('parent_id',null)->orderBy('name')->limit(5)->get() as $tag)
    		<li><a href="{{route('blogTag',$tag->slug)}}"> {{$tag->name}} </a></li>
    	 	@endforeach
		</ul>
	</div>
    --}}

    <div class="addBanner">
        <img src="{{asset('public/welcome/images/smbann.png')}}" alt="FSBS" />
    </div>
</div> 
