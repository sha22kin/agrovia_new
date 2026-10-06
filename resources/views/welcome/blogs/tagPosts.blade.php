@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle($tag->seo_title?:$tag->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{websiteTitle($tag->seo_title?:$category->name)}}" />
<meta name="description" property="og:description" content="{!!$tag->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$tag->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($tag->image())}}" />
<meta name="url" property="og:url" content="{{route('blogTag',$tag->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('blogTag',$tag->slug?:'no-title')}}">
@endsection @push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="breadcrumb-area"
@if($tag->bannerFile)
style="background-image:url({{asset($tag->banner())}});background-repeat: no-repeat;
    background-size: cover;padding: 50px 0;"
@endif
>
    <div class="container">
        <div class="title">
            <h1>{{$tag->name}}</h1>
            <ul>
                <li><a href="{{route('index')}}">Home</a></li>
                <li>{{$tag->name}}</li>
            </ul>
        </div>
    </div>
</div>

<div class="blogCompany">
    <div class="container">
		<div class="row">
		<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
            <div class="row">
                @foreach($posts as $post)
                <div class="col-md-6">
                    @include(welcomeTheme().'blogs.includes.blogGrid')
                </div>
                @endforeach
            </div>
			<!-- pagination -->
			{{$posts->links(welcomeTheme().'blogs.pagination')}}
		</div>
		<div class="col-lg-4 col-md-5  col-sm-12 col-xs-12">
			@include(welcomeTheme().'blogs.includes.sideBar')
		</div>
		</div>
	</div>
</div>

@endsection @push('js') @endpush