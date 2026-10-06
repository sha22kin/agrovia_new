{{-- Move a consultation to another time. Buttons with data-bs-target="#rescheduleModal" and data-id, data-topic, data-when open it. --}}
<!-- Move a consultation -->
<div class="modal fade" id="rescheduleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" action="#" id="rescheduleForm">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Reschedule consultation</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="fw-semibold mb-3" id="rescheduleTopic" style="font-size:.9rem;"></div>
          <label class="av-form-label">New date and time</label>
          <input type="datetime-local" class="form-control" name="scheduled_at" id="rescheduleWhen" required>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-av-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-av-primary">Move Session</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('js')
<script>
  (function () {
    var rescheduleUrl = @json(route('expert.consultationReschedule','__ID__'));

    // The Reschedule button of a session: its time is the starting point.
    document.getElementById('rescheduleModal').addEventListener('show.bs.modal', function (event) {
      var btn = event.relatedTarget;
      document.getElementById('rescheduleForm').action = rescheduleUrl.replace('__ID__', btn.dataset.id);
      document.getElementById('rescheduleTopic').textContent = btn.dataset.topic;
      document.getElementById('rescheduleWhen').value = btn.dataset.when || '';
    });
  })();
</script>
@endpush
