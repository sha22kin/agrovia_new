@extends(expertTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('My Articles')}}</title>
@endsection

@section('contents')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">My Articles</h5>
  <a href="{{route('expert.articleAction','create')}}" class="btn-av-primary"><i class="fa-solid fa-plus me-2"></i>Write New Article</a>
</div>

<div class="row g-3 mt-1">
  @forelse($articles as $article)
  @php $published = $article->status == 'active'; @endphp
  <div class="col-md-6 col-xl-4">
    <div class="av-article-card h-100">
      <img src="{{asset($article->image())}}" alt="{{$article->name}}" onerror="this.onerror=null;this.src='{{asset('public/medies/noimage.jpg')}}'">
      <div class="av-article-body">
        <span class="badge-status {{$published?'badge-active':'badge-pending'}} mb-2 d-inline-block">{{$published?'Published':'Draft'}}</span>
        <div class="fw-semibold" style="font-size:.87rem;">
          @if($published)<a href="{{route('blogView',$article->slug ?: 'no-title')}}" target="_blank" class="text-reset text-decoration-none">{{$article->name}}</a>@else{{$article->name}}@endif
        </div>
        <div class="text-muted mt-1" style="font-size:.74rem;">{{number_format($article->view)}} {{$article->view==1?'view':'views'}} &middot; {{$published ? $article->created_at?->format('M d, Y') : 'Not published'}}</div>
        <div class="d-flex gap-2 mt-2">
          <a href="{{route('expert.articleAction',['edit',$article->id])}}" class="btn-av-outline btn-sm py-1 px-2" title="Edit"><i class="fa-solid fa-pen"></i></a>
          <form action="{{route('expert.articleAction',['delete',$article->id])}}" method="POST" data-confirm="Delete &quot;{{Str::limit($article->name,60)}}&quot;? This cannot be undone." data-confirm-ok="Yes, delete" data-confirm-danger>@csrf<button type="submit" class="btn-av-danger" title="Delete"><i class="fa-solid fa-trash"></i></button></form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="av-card mt-0">
      <div class="av-empty"><i class="fa-solid fa-newspaper"></i>You have not written an article yet. <a href="{{route('expert.articleAction','create')}}">Write your first article</a></div>
    </div>
  </div>
  @endforelse
</div>

{{$articles->links('dashboard.partials.pagination')}}
@endsection
