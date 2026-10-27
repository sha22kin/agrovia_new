{{-- Inbox and chat shared by the buyer, seller and expert themes. Needs $role, $conversations, $selected, $draft, $peer, $thread, $version, $aboutAd. --}}
@php
  $me = Auth::id();
  $peerKey = $role == 'buyer' ? 'seller' : 'buyer';
  // On phones only one pane shows: the chat when one was opened, otherwise the list.
  $chatOpen = $peer && (request()->filled('c') || $draft);
@endphp
@push('css')
<link rel="stylesheet" href="{{assetV('public/dashboard/css/chat.css')}}">
@endpush

<h5 class="fw-bold mb-3">Messages</h5>
<div class="av-chat {{$chatOpen ? 'chat-open' : ''}}">
  <aside class="av-chat-side">
    <div class="av-chat-side-head">
      <h6>Chats</h6>
      <div class="av-chat-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="msgSearch" placeholder="Search name or message" autocomplete="off">
      </div>
    </div>
    <div class="av-chat-list" id="msgList">
      @include('dashboard.partials.message-list')
    </div>
  </aside>

  <section class="av-chat-main">
    @if($peer)
    <div class="av-chat-head">
      <a href="{{route($role.'.messages')}}" class="av-chat-back" title="All chats"><i class="fa-solid fa-arrow-left"></i></a>
      <img src="{{$peer->avatar(80)}}" alt="{{$peer->name}}">
      <div class="overflow-hidden">
        <div class="name text-truncate">{{$peer->name}}</div>
        {{-- An expert sees just the name of the farmer; a buyer / seller sees what the other one is. --}}
        @if($role != 'expert')
        <div class="sub text-truncate"><span class="role">{{$peer->isExpert() ? 'Expert' : ucfirst($peerKey)}}</span>@if($peer->isExpert() && $peer->expertProfile?->headline){{$peer->expertProfile->headline}}@elseif($peer->company_name){{$peer->company_name}}@endif</div>
        @endif
      </div>
    </div>

    <div class="av-thread" id="msgThread" data-v="{{$version}}" data-action-url="{{route($role.'.messageAction')}}">
      @if($selected)
      @include('dashboard.partials.message-thread', ['me' => $me])
      @else
      <div class="av-empty h-100" id="msgEmpty"><i class="fa-regular fa-hand"></i>Say hello to {{$peer->name}}.</div>
      @endif
    </div>

    <form method="POST" action="{{route($role.'.messageSend')}}" class="av-chat-compose" id="msgForm">
      @csrf
      @if($selected)<input type="hidden" name="conversation" value="{{$selected->id}}">@else<input type="hidden" name="to" value="{{$peer->id}}">@endif
      @if($aboutAd)
      <div class="av-msg-reply" id="msgReply">
        <input type="hidden" name="ad" value="{{$aboutAd->id}}">
        <a href="{{route('serviceView',$aboutAd->slug ?: 'no-title')}}" target="_blank" class="av-msg-ad">
          <img src="{{asset($aboutAd->fiImage())}}" alt="{{$aboutAd->name}}">
          <div class="overflow-hidden">
            <small>Replying about this ad</small>
            <b>{{$aboutAd->name}}</b>
            <span>৳{{number_format($aboutAd->price)}}</span>
          </div>
        </a>
        <a href="{{request()->fullUrlWithoutQuery('ad')}}" class="av-msg-reply-close" title="Don't attach this ad">&times;</a>
      </div>
      @endif
      <div class="av-msg-reply d-none" id="msgReplyTo">
        <input type="hidden" name="reply_to" value="">
        <div class="av-msg-quote">
          <small>Replying to <b data-who></b></small>
          <span data-text></span>
        </div>
        <a href="#" class="av-msg-reply-close" title="Cancel reply">&times;</a>
      </div>
      <div class="av-chat-input">
        <input type="text" class="@error('body') is-invalid @enderror" name="body" value="{{old('body')}}" maxlength="2000" placeholder="Write a message..." autocomplete="off" required>
        <button type="submit" class="av-chat-send" title="Send"><i class="fa-solid fa-paper-plane"></i></button>
      </div>
      <div class="invalid-feedback d-block" id="msgError">@error('body'){{$message}}@enderror</div>
    </form>
    @else
    <div class="av-empty"><i class="fa-regular fa-comments"></i>Select a chat to start messaging.</div>
    @endif
  </section>
</div>
@push('js')
<script>
  (function () {
    var thread = document.getElementById('msgThread');
    var list = document.getElementById('msgList');
    var form = document.getElementById('msgForm');
    var search = document.getElementById('msgSearch');
    var replyBox = document.getElementById('msgReplyTo');
    var pollUrl = @json(route($role.'.messagesPoll'));
    var conversationId = @json($selected?->id);
    var headers = { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };

    function scrollDown() { if (thread) { thread.scrollTop = thread.scrollHeight; } }
    function filterList() {
      var q = (search ? search.value : '').toLowerCase();
      document.querySelectorAll('.av-msg-item').forEach(function (el) {
        el.classList.toggle('d-none', (el.dataset.search || '').indexOf(q) === -1);
      });
    }
    // Puts a fresh copy of the chat in place; stays at the bottom when the reader was there.
    function renderThread(html, v, force) {
      if (!thread || typeof html !== 'string') { return; }
      // An open menu would close under the reader: try again on the next poll.
      if (!force && thread.querySelector('.dropdown-menu.show')) { return; }
      var atBottom = thread.scrollHeight - thread.scrollTop - thread.clientHeight < 80;
      var top = thread.scrollTop;
      thread.innerHTML = html;
      thread.dataset.v = v || '';
      if (atBottom || force) { scrollDown(); } else { thread.scrollTop = top; }
    }
    function jumpTo(id) {
      var row = document.getElementById('msg-' + id);
      if (!row) { return; }
      row.scrollIntoView({ behavior: 'smooth', block: 'center' });
      row.classList.add('flash');
      setTimeout(function () { row.classList.remove('flash'); }, 1500);
    }
    function clearReply() {
      if (!replyBox) { return; }
      replyBox.classList.add('d-none');
      replyBox.querySelector('input[name="reply_to"]').value = '';
    }

    scrollDown();
    if (search) { search.addEventListener('input', filterList); }

    // The open chat (when it changed) and the inbox list are fetched every few seconds, so nobody has to reload.
    var polling = false;
    function poll() {
      if (polling || document.hidden) { return; }
      polling = true;
      var url = pollUrl + (conversationId ? '?c=' + conversationId + '&v=' + encodeURIComponent(thread.dataset.v || '') : '');
      fetch(url, { headers: headers, credentials: 'same-origin' })
        .then(function (res) { return res.ok ? res.json() : null; })
        .then(function (data) {
          if (!data) { return; }
          if (data.thread !== undefined) { renderThread(data.thread, data.v); }
          if (list && typeof data.list === 'string') { list.innerHTML = data.list; filterList(); }
        })
        .catch(function () {})
        .finally(function () { polling = false; });
    }
    setInterval(poll, 4000);
    document.addEventListener('visibilitychange', function () { if (!document.hidden) { poll(); } });

    // Reply, like, pin and delete on a message.
    if (thread) {
      thread.addEventListener('click', function (e) {
        var jump = e.target.closest('[data-jump]');
        if (jump) { e.preventDefault(); jumpTo(jump.dataset.jump); return; }

        var reply = e.target.closest('[data-reply]');
        if (reply && replyBox) {
          replyBox.querySelector('input[name="reply_to"]').value = reply.dataset.reply;
          replyBox.querySelector('[data-who]').textContent = reply.dataset.who;
          replyBox.querySelector('[data-text]').textContent = reply.dataset.preview;
          replyBox.classList.remove('d-none');
          form.querySelector('input[name="body"]').focus();
          return;
        }

        var action = e.target.closest('[data-action]');
        if (!action) {
          // Touch screens have no hover: tapping a message shows its buttons (and hides the others).
          var tapped = e.target.closest('.av-thread-bubble');
          if (tapped && !e.target.closest('a')) {
            var tappedRow = tapped.closest('.av-msg-row');
            thread.querySelectorAll('.av-msg-row.show-actions').forEach(function (r) { if (r !== tappedRow) { r.classList.remove('show-actions'); } });
            tappedRow.classList.toggle('show-actions');
          }
          return;
        }
        if (action.dataset.action === 'delete_all' && !confirm('Delete this message for everyone?')) { return; }
        var row = action.closest('.av-msg-row');
        var body = new FormData();
        body.append('_token', form.querySelector('input[name="_token"]').value);
        body.append('message', row.dataset.id);
        body.append('action', action.dataset.action);
        fetch(thread.dataset.actionUrl, { method: 'POST', body: body, headers: headers, credentials: 'same-origin' })
          .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
          .then(function (r) {
            if (!r.ok) { alert((r.data && r.data.message) || 'That did not work. Please try again.'); poll(); return; }
            renderThread(r.data.thread, r.data.v, false);
          })
          .catch(function () { alert('That did not work. Check your connection.'); });
      });
    }
    if (replyBox) {
      replyBox.querySelector('.av-msg-reply-close').addEventListener('click', function (e) { e.preventDefault(); clearReply(); });
    }

    // Sending without leaving the page.
    if (form) {
      var input = form.querySelector('input[name="body"]');
      var button = form.querySelector('button[type="submit"]');
      var error = document.getElementById('msgError');
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!input.value.trim()) { return; }
        button.disabled = true;
        error.textContent = '';
        input.classList.remove('is-invalid');
        fetch(form.action, { method: 'POST', body: new FormData(form), headers: headers, credentials: 'same-origin' })
          .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
          .then(function (r) {
            if (!r.ok) {
              var msg = r.data && r.data.errors && r.data.errors.body ? r.data.errors.body[0] : (r.data && r.data.message) || 'The message could not be sent.';
              error.textContent = msg;
              input.classList.add('is-invalid');
              return;
            }
            // The first message of a new chat creates the conversation: open it.
            if (!conversationId) { window.location = r.data.url; return; }
            input.value = '';
            clearReply();
            var adReply = document.getElementById('msgReply');
            if (adReply) { adReply.remove(); history.replaceState(null, '', r.data.url); }
            renderThread(r.data.thread, r.data.v, true);
          })
          .catch(function () { error.textContent = 'The message could not be sent. Check your connection.'; })
          .finally(function () { button.disabled = false; input.focus(); });
      });
    }
  })();
</script>
@endpush