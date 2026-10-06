@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle($post->seo_title?:$post->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{websiteTitle($post->seo_title?:$post->name)}}" />
<meta name="description" property="og:description" content="{!!$post->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$post->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($post->image())}}" />
<meta name="url" property="og:url" content="{{route('blogView',$post->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('blogView',$post->slug?:'no-title')}}">
@endsection @push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$post->name}}</h1>
    </div>
</div>

<div class="blogCompany">
    <div class="container">
		<div class="row">
		    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
		        
		        <div class="detailsBlogView">
		            
                    <h2 class="blog_title">{{$post->name}}</h2>
                    <h4>{{$post->created_at->format('d M, Y')}}</h4>
                    
                    <div class="single_blog_thumb">
                        <img src="{{asset($post->image())}}" alt="{{$post->name}}" style="width:100%;" /> 
                    </div>
                    
                    <div class="single-blog-content">
                        {!!$post->description!!}
                    </div>
                    
                    {{--<div class="single-blog-comment">
                        <h3>{{$post->postComments->where('status','active')->count()}} Comments</h3>
                        @foreach($comments as $comment)
                        <div class="card mb-3" style="max-width: 100%;">
                            <div class="row g-0 m-0">
                                <div class="col-md-4" style="max-width: 100px;padding: 5px;text-align: center;">
                                    <img src="{{asset($comment->image())}}" class="img-fluid rounded-start" style="max-height:80px;" alt="{{$comment->name}}">
                                </div>
                                <div class="col-md-8" style="padding: 5px;">
                                <div class="card-body" style="padding:5px 10px;">
                                    <h5 style="margin:0;" >
                                        {{$comment->name}}
                                    </h5>
                                    <span>{{$comment->created_at->format('F d, Y \a\t g:ia')}}</span>
                                    <p style="margin:0;">{!!$comment->content!!}</p>
                                </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @if($comments->count()==0)
                        <span>No Comment Yet</span>
                        @endif
                        <div class="commentForm">
                            <h4>Leave A Reply</h4>
                            <form action="{{route('blogComments',$post->slug)}}" method="post">
                            @csrf
                            <div class="form-group">
                                @if ($errors->has('comment'))
                                <p style="color: red; margin: 0;">{{ $errors->first('comment') }}</p>
                                @endif
                                <textarea rows="5" class="form-control" name="comment" placeholder="Write Your Comment*" required="">{{old('comment')}}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control" required="" placeholder="Your name*">
                                    @if ($errors->has('name'))
                                    <p style="color: red; margin: 0;">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control" required="" placeholder="Your Email*">
                                    @if ($errors->has('email'))
                                    <p style="color: red; margin: 0;">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" name="website" value="{{old('website')}}" class="form-control" required="" placeholder="Your website*">
                                @if ($errors->has('website'))
                                    <p style="color: red; margin: 0;">{{ $errors->first('website') }}</p>
                                    @endif
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                     </div>--}}
                </div>
                
                <div class="single-blog-comment">
                    <h3>{{$post->postComments->where('status','active')->count()}} Comments</h3>
                    @foreach($comments as $comment)
                    <div class="card mb-3" style="max-width: 100%;">
                        <div class="row g-0 m-0">
                            <div class="col-md-4" style="max-width: 100px; padding: 5px;text-align: center;">
                                <img src="{{asset($comment->image())}}" class="img-fluid rounded-start" style="max-height:80px;" alt="{{$comment->name}}" />
                            </div>
                            <div class="col-md-8" style="padding: 5px;">
                                <div class="card-body" style="padding: 5px 10px;">
                                    <h5 style="margin:0;">
                                        {{$comment->name}}
                                    </h5>
                                    <span>{{$comment->created_at->format('F d, Y \a\t g:ia')}}</span>
                                    <p style="margin:0;">{!!$comment->content!!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if($comments->count()==0)
                    <span>No Comment Yet</span>
                    @endif
                    <hr>
                    <div class="commentForm">
                        <h4>Leave A Reply</h4>
                        <form action="{{route('blogComments',$post->slug)}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" value="{{old('name')}}" class="form-control" required="" placeholder="Full Name">
                                @if ($errors->has('name'))
                                <p style="color: red; margin: 0;">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="email" name="email" value="{{old('email')}}" class="form-control" required="" placeholder="Email Address">
                                @if ($errors->has('email'))
                                <p style="color: red; margin: 0;">{{ $errors->first('email') }}</p>
                                @endif
                            </div>
                        </div>
                        {{--<div class="form-group">
                            <input type="text" name="website" value="{{old('website')}}" class="form-control" required="" placeholder="Your website">
                            @if ($errors->has('website'))
                                <p style="color: red; margin: 0;">{{ $errors->first('website') }}</p>
                                @endif
                        </div>--}}
                        <div class="form-group">
                            @if ($errors->has('comment'))
                            <p style="color: red; margin: 0;">{{ $errors->first('comment') }}</p>
                            @endif
                            <textarea rows="5" class="form-control" name="comment" placeholder="Write Your Comment" required="">{{old('comment')}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Send Comment</button>
                        </form>
                    </div>
                </div>
		    </div>
		    <div class="col-lg-4 col-md-5  col-sm-12 col-xs-12">
			    @include(welcomeTheme().'blogs.includes.sideBar')
		    </div>
		</div>
	</div>
</div>

@endsection @push('js') @endpush