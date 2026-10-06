{{-- Answer a question of a farmer. Buttons with data-bs-target="#answerModal" and data-id, data-title, data-body open it. --}}
<!-- Answer a question -->
<div class="modal fade" id="answerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" action="#" id="answerForm">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold">Answer the question</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="fw-semibold" id="answerTitle" style="font-size:.9rem;"></div>
          <div class="text-secondary mt-1 mb-3" id="answerBody" style="font-size:.82rem;"></div>
          <label class="av-form-label">Your answer</label>
          <textarea class="form-control" name="body" rows="6" maxlength="5000" placeholder="Explain the cause and what the farmer should do" required></textarea>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-av-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-av-primary">Post Answer</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('js')
<script>
  (function () {
    var answerUrl = @json(route('expert.questionAnswer','__ID__'));

    // The Answer button of a question fills the modal with that question.
    document.getElementById('answerModal').addEventListener('show.bs.modal', function (event) {
      var btn = event.relatedTarget;
      document.getElementById('answerForm').action = answerUrl.replace('__ID__', btn.dataset.id);
      document.getElementById('answerTitle').textContent = btn.dataset.title;
      document.getElementById('answerBody').textContent = btn.dataset.body || '';
      document.querySelector('#answerForm textarea').value = '';
    });
  })();
</script>
@endpush
