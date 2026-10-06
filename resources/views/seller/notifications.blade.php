@extends(sellerTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Notifications')}}</title>
@endsection

@section('contents')
<style>
  .nt-card { padding: 0; overflow: hidden; }
  .nt-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; padding: 18px 22px; border-bottom: 1px solid #edf2ee; }
  .nt-head h2 { font-size: 1rem; font-weight: 600; margin: 0; color: #1f2a22; }
  .nt-head small { display: block; font-size: .78rem; color: #8a978e; margin-top: 2px; }
  .nt-clear { display: inline-flex; align-items: center; gap: 6px; border: 1px solid #f1c9cc; background: #fff; color: #c62828; border-radius: 8px; padding: 6px 12px; font-size: .8rem; font-weight: 500; cursor: pointer; transition: background .15s ease; }
  .nt-clear:hover { background: #fdecec; }

  .nt-list { list-style: none; margin: 0; padding: 0; }
  .nt-item { position: relative; display: flex; gap: 14px; padding: 16px 22px; border-bottom: 1px solid #f0f4f1; transition: background .15s ease; }
  .nt-item:last-child { border-bottom: 0; }
  .nt-item:hover { background: #fafcfa; }
  .nt-item.unread { background: #f6fbf7; }
  .nt-item.unread::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: #1b7a3a; }

  .nt-icon { flex: 0 0 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .9rem; }
  .nt-icon.ok { background: #e6f4e9; color: #1b7a3a; }
  .nt-icon.no { background: #fdecec; color: #c62828; }

  .nt-body { flex: 1; min-width: 0; }
  .nt-top { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; }
  .nt-title { font-weight: 600; font-size: .88rem; color: #1f2a22; display: flex; align-items: center; gap: 8px; min-width: 0; }
  .nt-time { flex: 0 0 auto; font-size: .74rem; color: #8a978e; white-space: nowrap; }
  .nt-new { background: #1b7a3a; color: #fff; border-radius: 6px; padding: 1px 6px; font-size: .6rem; font-weight: 600; letter-spacing: .03em; }
  .nt-text { font-size: .83rem; color: #4b5b50; margin-top: 2px; overflow-wrap: anywhere; }
  .nt-reason { margin-top: 8px; padding: 8px 12px; background: #fff5f5; border: 1px solid #f6d6d8; border-radius: 8px; font-size: .8rem; color: #842029; overflow-wrap: anywhere; }
  .nt-reason b { font-weight: 600; }

  .nt-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
  .nt-btn { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; font-size: .76rem; font-weight: 500; text-decoration: none; border: 1px solid #d6e6da; color: #1b7a3a; background: #fff; transition: background .15s ease; }
  .nt-btn:hover { background: #eef7f0; color: #145c2c; }
  .nt-btn.warn { border-color: #f1d9b5; color: #a35e05; }
  .nt-btn.warn:hover { background: #fff6e8; }

  .nt-del { position: absolute; top: 12px; right: 14px; width: 28px; height: 28px; border: 0; border-radius: 8px; background: transparent; color: #a3b0a7; display: flex; align-items: center; justify-content: center; cursor: pointer; opacity: .6; transition: opacity .15s ease, background .15s ease, color .15s ease; }
  .nt-item:hover .nt-del, .nt-del:focus-visible { opacity: 1; }
  .nt-del:hover { background: #fdecec; color: #c62828; }
  .nt-item .nt-time { margin-right: 30px; }

  .nt-empty { text-align: center; padding: 48px 20px; color: #8a978e; }
  .nt-empty i { display: block; font-size: 1.8rem; color: #b9cfbf; margin-bottom: 10px; }
  .nt-empty b { display: block; color: #1f2a22; font-weight: 600; margin-bottom: 4px; }
  .nt-pages { padding: 12px 22px; border-top: 1px solid #edf2ee; }

  @media (max-width: 575.98px) {
    .nt-head, .nt-item { padding-left: 16px; padding-right: 16px; }
    .nt-top { flex-direction: column; gap: 2px; }
    .nt-item .nt-time { margin-right: 0; }
    .nt-title { padding-right: 28px; }
  }
</style>

<h1 class="av-page-title">Notifications</h1>
@include('dashboard.alerts')

<div class="av-card first mt-2 nt-card">
  <div class="nt-head">
    <div>
      <h2>Messages from Agroviea</h2>
      <small>{{ $notifications->total() }} {{ \Illuminate\Support\Str::plural('notification', $notifications->total()) }}@if($unreadIds->count()) · {{ $unreadIds->count() }} new @endif</small>
    </div>
    @if($notifications->total())
    <form action="{{ route('seller.notificationsClear') }}" method="POST" onsubmit="return confirm('Clear all notifications? This cannot be undone.')">
      @csrf
      <button type="submit" class="nt-clear"><i class="fa-regular fa-trash-can"></i> Clear all</button>
    </form>
    @endif
  </div>

  @if($notifications->count())
  <ul class="nt-list">
    @foreach($notifications as $note)
    @php
      $data = $note->data;
      $rejected = ($data['status'] ?? '') == 'rejected';
      $ad = isset($data['ad_id']) ? Auth::user()->myListings()->find($data['ad_id'], ['id', 'slug', 'status']) : null;
      $isNew = $unreadIds->contains($note->id);
    @endphp
    <li class="nt-item {{ $isNew ? 'unread' : '' }}">
      <div class="nt-icon {{ $rejected ? 'no' : 'ok' }}"><i class="fa-solid {{ $rejected ? 'fa-xmark' : 'fa-check' }}"></i></div>
      <div class="nt-body">
        <div class="nt-top">
          <div class="nt-title">{{ $data['title'] ?? 'Notification' }} @if($isNew)<span class="nt-new">NEW</span>@endif</div>
          <span class="nt-time" title="{{ $note->created_at->format('d M Y, h:i A') }}">{{ $note->created_at->diffForHumans() }}</span>
        </div>
        <div class="nt-text">{{ $data['message'] ?? '' }}</div>
        @if($rejected)
        <div class="nt-reason"><b>Reason from admin:</b> {{ $data['reason'] ?? 'No reason given.' }}</div>
        @endif
        @if($ad && in_array($ad->status, ['cancelled', 'active']))
        <div class="nt-actions">
          @if($ad->status == 'cancelled')
          <a href="{{ route('seller.listingsAction', ['edit', $ad->id]) }}" class="nt-btn warn"><i class="fa-solid fa-pen"></i>Fix &amp; resubmit</a>
          @else
          <a href="{{ route('serviceView', $ad->slug ?: 'no-slug') }}" target="_blank" class="nt-btn"><i class="fa-regular fa-eye"></i>View ad</a>
          @endif
        </div>
        @endif
      </div>
      <form action="{{ route('seller.notificationDelete', $note->id) }}" method="POST">
        @csrf
        <button type="submit" class="nt-del" title="Remove" aria-label="Remove notification"><i class="fa-solid fa-xmark"></i></button>
      </form>
    </li>
    @endforeach
  </ul>
  @else
  <div class="nt-empty">
    <i class="fa-regular fa-bell"></i>
    <b>You're all caught up</b>
    You will hear here when the admin approves or rejects your ads.
  </div>
  @endif

  @if($notifications->hasPages())
  <div class="nt-pages">{{ $notifications->links('pagination') }}</div>
  @endif
</div>
@endsection
