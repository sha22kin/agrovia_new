<div class="blogGrid">
	<div class="image">
		<a href="{{route('blogView',$post->slug?:'no-title')}}">
			<img src="{{asset($post->image())}}" alt="FSBS" class="img-responsive img-fluid" />
		</a>
		<h4>{{$post->created_at->format('M')}}<br>{{$post->created_at->format('d')}} </h4>
	</div>
 
    <div class="blog-content">
   		<span>Blogs & Guides</span>
		<h5><a href="{{route('blogView',$post->slug?:'no-title')}}">{{$post->name}}</a></h5>
    </div>
</div>