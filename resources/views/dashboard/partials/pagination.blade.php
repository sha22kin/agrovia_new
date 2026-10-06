{{-- Pagination of the dashboard pages: Prev, page numbers, Next and a "Showing 1-8 of 20" line. Used as $paginator->links('dashboard.partials.pagination'). --}}
@if ($paginator->total() > 0)
<nav class="mt-4" aria-label="Pagination">
  @if ($paginator->hasPages())
  <ul class="pagination pagination-sm justify-content-center mb-2">
    @if ($paginator->onFirstPage())
    <li class="page-item disabled"><span class="page-link">Prev</span></li>
    @else
    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prev</a></li>
    @endif

    @foreach ($elements as $element)
      @if (is_string($element))
      <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
          <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
          @else
          <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
    @else
    <li class="page-item disabled"><span class="page-link">Next</span></li>
    @endif
  </ul>
  @endif
  @if ($paginator->count() > 0)
  <div class="text-center text-muted" style="font-size:.78rem;">Showing {{ number_format($paginator->firstItem()) }} - {{ number_format($paginator->lastItem()) }} of {{ number_format($paginator->total()) }}</div>
  @else
  {{-- A page past the end, e.g. after the last item of the last page was removed. --}}
  <div class="text-center text-muted" style="font-size:.78rem;">This page is empty. <a href="{{ $paginator->url($paginator->lastPage()) }}" class="av-link-more">Go to the last page</a></div>
  @endif
</nav>
@endif
