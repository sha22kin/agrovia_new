{{-- Share buttons of the community posts: the share sheet where the browser has one, else copy the link. --}}
@push('js')
<script>
  document.querySelectorAll('[data-share]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url = new URL(btn.getAttribute('data-share'), location.href).href;
      if (navigator.share) {
        navigator.share({title: btn.getAttribute('data-share-title'), url: url}).catch(function () {});
      } else if (navigator.clipboard) {
        navigator.clipboard.writeText(url);
        avToast('success', 'Post link copied');
      }
    });
  });
</script>
@endpush
