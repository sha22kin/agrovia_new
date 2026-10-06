@extends(welcomeTheme().'layouts.app') @section('title')
<title>{{websiteTitle('Search')}}</title>
@endsection @section('SEO')
<meta name="title" property="og:title" content="{{websiteTitle('Search')}}" />
<meta name="description" property="og:description" content="{!!general()->meta_description!!}" />
<meta name="keywords" content="{{general()->meta_keyword}}" />
<meta name="image" property="og:image" content="{{asset(general()->logo())}}" />
<meta name="url" property="og:url" content="{{route('search')}}" />
<link rel="canonical" href="{{route('search')}}">
@endsection @push('css')
<style>

</style>
@endpush

@section('contents')

<div class="pageHead">
    <div class="container">
        <h1>{{$r->search?:'Search'}}</h1>
    </div>
</div>

<div class="serviceCompany">
    <div class="container">
        @if($r->search)
        <div class="mb-3">
            @guest
            <a href="{{route('login')}}" class="btn btn-sm btn-outline-success"><i class="fa fa-search"></i> Log in to save this search</a>
            @else
            @if(auth()->user()->isBuyer())
            <form action="{{route('buyer.savedSearchAction','save')}}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="keyword" value="{{$r->search}}">
                <input type="hidden" name="category" value="{{$r->category}}">
                <input type="hidden" name="district" value="{{$r->district}}">
                <input type="hidden" name="min_price" value="{{$r->min_price}}">
                <input type="hidden" name="max_price" value="{{$r->max_price}}">
                <button type="submit" class="btn btn-sm btn-outline-success"><i class="fa fa-search"></i> Save this search</button>
            </form>
            @endif
            @endguest
        </div>
        @endif
        <div class="listingParts">
            <div class="row">
                @forelse($posts as $service)
                <div class="col-md-3 col-6">
                    @include(welcomeTheme().'services.includes.serviceGrid')
                </div>
                @empty
                <div class="col-12">
                    <p>{{$r->search?'No ads found for your search.':'Type something to search the ads.'}}</p>
                </div>
                @endforelse
            </div>
            <!-- pagination -->
            {{$posts->links('pagination')}}
        </div>
    </div>
</div>

@endsection
@push('js') @endpush
