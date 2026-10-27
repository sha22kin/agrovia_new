{{-- Inbox and chat shared by the buyer and seller themes. Needs $role, $conversations, $selected, $draft, $peer, $thread. --}}
@php
  $me = Auth::id();
  $peerKey = $role == 'buyer' ? 'seller' : 'buyer';
@endphp
<h5 class="fw-bold mb-3">Messages</h5>
<div class="av-card mt-0 p-2 p-md-3">
  <div class="row g-0">
    <div class="col-12 col-md-4 border-end" style="max-height:520px;overflow-y:auto;">
      <div class="p-2">
        <input type="text" id="msgSearch" class="form-control mb-2" placeholder="Search messages...">

        <div id="msgList">
          @include('dashboard.partials.message-list')
        </div>
      </div>
    </div>

    <div class="col-12 col-md-8 d-flex flex-column" style="min-height:420px;">
      @if($peer)
      <div class="d-flex align-items-center gap-2 p-3 border-bottom">
        <img src="{{$peer->avatar(80)}}" alt="{{$peer->name}}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
        <div>
          <div class="fw-semibold" style="font-size:.87rem;">{{$peer->name}}</div>
          {{-- An expert sees just the name of the farmer; a buyer / seller sees what the other one is. --}}
          @if($role != 'expert')
          <div class="text-muted" style="font-size:.72rem;">{{$peer->isExpert() ? 'Expert' : ucfirst($peerKey)}}@if($peer->isExpert() && $peer->expertProfile?->headline) · {{$peer->expertProfile->headline}}@elseif($peer->company_name) · {{$peer->company_name}}@endif</div>
          @endif
        </div>
      </div>
      <div class="flex-grow-1 p-3 av-thread" id="msgThread">
        @forelse($thread as $message)
        @include('dashboard.partials.message-bubble')
        @empty
        <div class="av-empty" id="msgEmpty"><i class="fa-regular fa-message"></i>Say hello to {{$peer->name}}.</div>
        @endforelse
      </div>
      <form method="POST" action="{{route($role.'.messageSend')}}" class="p-3 border-top" id="msgForm">
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
        <div class="d-flex gap-2">
          <input type="text" class="form-control @error('body') is-invalid @enderror" name="body" value="{{old('body')}}" maxlength="2000" placeholder="Type a message..." autocomplete="off" required>
          <button type="submit" class="btn-av-primary" title="Send"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
        <div class="invalid-feedback d-block" id="msgError">@error('body'){{$message}}@enderror</div>
      </form>
      @else
      <div class="av-empty my-auto"><i class="fa-regular fa-comments"></i>Select a conversation to read it.</div>
      @endif
    </div>
  </div>
</div>

@push('js')
<script>
  (function () {
    var thread = document.getElementById('msgThread');
    var list = document.getElementById('msgList');
    var form = document.getElementById('msgForm');
    var search = document.getElementById('msgSearch');
    var pollUrl = @json(route($role.'.messagesPoll'));
    var conversationId = @json($selected?->id);

    function scrollDown() { if (thread) { thread.scrollTop = thread.scrollHeight; } }
    function lastId() {
      var bubbles = thread ? thread.querySelectorAll('.av-thread-bubble[data-id]') : [];
      return bubbles.length ? bubbles[bubbles.length - 1].dataset.id : 0;
    }
    function filterList() {
      var q = (search ? search.value : '').toLowerCase();
      document.querySelectorAll('.av-msg-item').forEach(function (el) {
        el.classList.toggle('d-none', (el.dataset.search || '').indexOf(q) === -1);
      });
    }
    // Adds bubbles the thread does not show yet (a poll and a send can return the same message).
    function appendBubbles(html) {
      if (!thread || !html) { return; }
      var box = document.createElement('div');
      box.innerHTML = html;
      var added = false;
      box.querySelectorAll('.av-thread-bubble').forEach(function (bubble) {
        if (!thread.querySelector('.av-thread-bubble[data-id="' + bubble.dataset.id + '"]')) {
          thread.appendChild(bubble);
          added = true;
        }
      });
      if (added) {
        var empty = document.getElementById('msgEmpty');
        if (empty) { empty.remove(); }
        scrollDown();
      }
    }

    scrollDown();
    if (search) { search.addEventListener('input', filterList); }

    // New messages and the inbox list are fetched every few seconds, so nobody has to reload the page.
    var polling = false;
    function poll() {
      if (polling || document.hidden) { return; }
      polling = true;
      var url = pollUrl + '?after=' + lastId() + (conversationId ? '&c=' + conversationId : '');
      fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
        .then(function (res) { return res.ok ? res.json() : null; })
        .then(function (data) {
          if (!data) { return; }
          appendBubbles(data.html);
          if (list && typeof data.list === 'string') { list.innerHTML = data.list; filterList(); }
        })
        .catch(function () {})
        .finally(function () { polling = false; });
    }
    setInterval(poll, 4000);
    document.addEventListener('visibilitychange', function () { if (!document.hidden) { poll(); } });

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
        fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin'
        })
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
            var reply = document.getElementById('msgReply');
            if (reply) { reply.remove(); history.replaceState(null, '', r.data.url); }
            appendBubbles(r.data.html);
            poll();
          })
          .catch(function () { error.textContent = 'The message could not be sent. Check your connection.'; })
          .finally(function () { button.disabled = false; input.focus(); });
      });
    }
  })();
</script>
@endpush
