@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle($category->seo_title?:$category->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{websiteTitle($category->seo_title?:$category->name)}}" />
<meta name="description" property="og:description" content="{!!$category->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$category->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($category->image())}}" />
<meta name="url" property="og:url" content="{{route('blogCategory',$category->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('blogCategory',$category->slug?:'no-title')}}">
@endsection @push('css')
<style>

</style>
@endpush 
@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$category->name}}</h1>
    </div>
</div>

<div class="blogCompany">
    <div class="container">
        <div class="row">
            @foreach($posts as $post)
            <div class="col-md-4">
                @include(welcomeTheme().'blogs.includes.blogGrid')
            </div>
            @endforeach
        </div>
		<!-- pagination -->
		{{$posts->links(welcomeTheme().'blogs.pagination')}}
	</div>
</div>

@endsection @push('js') @endpush