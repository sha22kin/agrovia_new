{{-- Seller dashboard footer. --}}
<footer class="av-footer d-flex flex-wrap justify-content-between gap-1">
  <span>Copyright &copy; {{Carbon\Carbon::now()->format('Y')}} <a href="{{route('index')}}">{{general()->title}}</a></span>
  <span class="d-none d-md-inline">Development By <a href="https://natoreit.com/" target="_blank" rel="noopener">Natore-IT</a></span>
</footer>
