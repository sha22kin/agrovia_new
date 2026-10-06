@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Search')}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{websiteTitle('Search')}}" />
<meta name="description" property="og:description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset(general()->logo())}}" />
<meta name="url" property="og:url" content="{{route('blogSearch')}}" />
<link rel="canonical" href="{{route('blogSearch')}}">
@endsection @push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{request()->search}}</h1>
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

@endsection 
@push('js') @endpush