@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{$page->seo_title?:websiteTitle($page->name)}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{$page->seo_title?:websiteTitle($page->name)}}" />
<meta name="description" property="og:description" content="{!!$page->seo_description?:general()->meta_description!!}" />
<meta name="keywords" content="{{$page->seo_keyword?:general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset($page->image())}}" />
<meta name="url" property="og:url" content="{{route('pageView',$page->slug?:'no-title')}}" />
<link rel="canonical" href="{{route('pageView',$page->slug?:'no-title')}}">
@endsection
@push('css')
<style>

</style>
@endpush 

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$page->name}}</h1>
    </div>
</div>

<div class="careerPage">
    <div class="container">
        <div class="careerFirst">
            <h2>Join Our Team!</h2>
            <p>
                We’re Transforming an Industry. Come Grow with Us.
            </p>
        </div>
        
        <div class="careerFirst">
            <h2><span>Opportunity Is</span> Always Knocking Here</h2>
            <p>
                Marketing? IT? Logistics? Product development? Customer service? Whatever your talents and passions, chances are we’ve got an opportunity for you.
            </p>
        </div>
        
        <a href="#" class="careerBtn">VIEW OUR OPEN POSITIONS</a>
    </div>
</div>

@endsection 
@push('js') 
@endpush