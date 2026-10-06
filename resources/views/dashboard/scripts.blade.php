{{-- Scripts shared by the buyer and seller layouts: district -> thana/city select and CSRF for ajax. --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

  $(document).on('change', '[data-geo-target]', function () {
    var target = $($(this).data('geo-target')), id = $(this).val();
    target.empty().append('<option value="">Select Option</option>');
    if (id) {
      $.get('{{url('geo/filter')}}/' + id, function (data) {
        target.empty().append(data.geoData);
      });
    }
  });
</script>
