{{-- Reject an ad with a reason. Opened by any .js-reject button (data-url = reject action, data-name = ad title).
     The reason is sent to the seller as a notification and shown on their My Ads page. --}}
<style>
    #rejectDialog { border: 0; border-radius: 10px; padding: 0; width: min(480px, calc(100vw - 32px)); box-shadow: 0 20px 50px rgba(0,0,0,.25); }
    #rejectDialog::backdrop { background: rgba(20, 30, 40, .45); }
    #rejectDialog .rd-head { padding: 14px 18px; border-bottom: 1px solid #eef2f6; font-weight: 700; display: flex; justify-content: space-between; align-items: center; }
    #rejectDialog .rd-body { padding: 16px 18px; }
    #rejectDialog .rd-foot { padding: 12px 18px; border-top: 1px solid #eef2f6; display: flex; justify-content: flex-end; gap: 8px; }
    #rejectDialog .quick { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }
    #rejectDialog .quick button { border: 1px solid #e3ebf3; background: #f5f7fa; border-radius: 14px; padding: 3px 10px; font-size: 12px; cursor: pointer; }

</style>

<dialog id="rejectDialog">
    <form method="POST" id="rejectForm">
        @csrf
        <div class="rd-head">
            <span><i class="fa-solid fa-ban" style="color: #f6a609;"></i> Reject ad</span>
            <button type="button" class="close js-reject-close" aria-label="Close" style="border: 0; background: none; font-size: 22px; line-height: 1;">&times;</button>
        </div>
        <div class="rd-body">
            <p class="mb-1" style="color: #4a5568;">Ad: <b id="rejectAdName"></b></p>
            <label for="rejectReason" class="mb-50" style="font-weight: 600;">Why is this ad rejected? <span style="color: #ff4961;">*</span></label>
            <div class="quick">
                <button type="button" data-text="Photos are missing or not clear. Please add real photos of the product.">Unclear photos</button>
                <button type="button" data-text="Wrong category selected. Please choose the right category.">Wrong category</button>
                <button type="button" data-text="Price is missing or not realistic. Please give the correct price.">Wrong price</button>
                <button type="button" data-text="Description is not enough. Please describe the product clearly.">Poor description</button>
                <button type="button" data-text="This item is not allowed on Agroviea.">Not allowed</button>
            </div>
            <textarea id="rejectReason" name="reject_reason" class="form-control" rows="4" maxlength="1000" required placeholder="Write the reason. The seller will see this message."></textarea>
            <small style="color: #8a96a3;">The seller gets this as a notification and can fix the ad and submit again.</small>
        </div>
        <div class="rd-foot">
            <button type="button" class="btn btn-outline-secondary js-reject-close">Cancel</button>
            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-paper-plane"></i> Reject &amp; notify seller</button>
        </div>
    </form>
</dialog>

@push('js')
<script>
    (function () {
        var dialog = document.getElementById('rejectDialog');
        var form = document.getElementById('rejectForm');
        var reason = document.getElementById('rejectReason');

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.js-reject');
            if (btn) {
                form.action = btn.dataset.url;
                document.getElementById('rejectAdName').textContent = btn.dataset.name;
                reason.value = '';
                dialog.showModal();
                reason.focus();
                return;
            }
            if (e.target.closest('.js-reject-close')) {
                dialog.close();
                return;
            }
            var quick = e.target.closest('#rejectDialog .quick button');
            if (quick) {
                reason.value = reason.value ? reason.value + ' ' + quick.dataset.text : quick.dataset.text;
                reason.focus();
            }
        });
    })();
</script>
@endpush
