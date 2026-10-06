@extends(adminTheme().'layouts.app')
@section('title')
<title>{{websiteTitle('Buyer Enquiries')}}</title>
@endsection

@push('css')
<style type="text/css">
  .eq-tabs { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px; }
  .eq-tabs a { border:1px solid #e3ebf3; border-radius:16px; padding:4px 12px; font-size:13px; color:#555; }
  .eq-tabs a.active { background:#1c7a44; border-color:#1c7a44; color:#fff; }
  .eq-title { font-weight:600; color:#2a2e30; }
  .eq-desc { font-size:12.5px; color:#6b7f75; margin-top:4px; white-space:pre-line; max-width:520px; overflow-wrap:anywhere; }
  .eq-reason { font-size:12.5px; color:#a94442; margin-top:4px; }
  .eq-reject textarea { font-size:13px; }
</style>
@endpush
@section('contents')

<div class="content-header row">
  <div class="content-header-left col-md-6 col-12 mb-2">
    <h3 class="content-header-title mb-0">Buyer Enquiries</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard </a></li>
          <li class="breadcrumb-item active">Buyer Enquiries</li>
        </ol>
      </div>
    </div>
  </div>
  <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2">
    <div class="btn-group float-md-right" role="group">
      <a class="btn btn-outline-primary" href="{{route('admin.enquiries',['approval'=>$approval])}}"><i class="fa-solid fa-rotate"></i></a>
    </div>
  </div>
</div>

<div class="content-body">
  <section class="basic-elements">
    <div class="row">
      <div class="col-md-12">
        @include(adminTheme().'alerts')

        <div class="card">
          <div class="card-header" style="border-bottom: 1px solid #e3ebf3;">
            <h4 class="card-title">Enquiries posted by buyers</h4>
            <p class="mb-0 mt-1 text-muted" style="font-size:13px;">Approved enquiries appear on the RFQ page and in the sellers' Buyer Requirements. Rejected ones go back to the buyer with your reason.</p>
          </div>
          <div class="card-content">
            <div class="card-body">
              <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="eq-tabs">
                  @foreach(\App\Models\Enquiry::APPROVALS as $key => $label)
                  <a href="{{route('admin.enquiries',['approval'=>$key])}}" class="{{$approval==$key?'active':''}}">{{$label}} ({{$counts[$key] ?? 0}})</a>
                  @endforeach
                </div>
                <form action="{{route('admin.enquiries')}}" class="mb-1">
                  <input type="hidden" name="approval" value="{{$approval}}">
                  <div class="input-group">
                    <input type="text" name="search" value="{{request()->search}}" placeholder="Search title" class="form-control">
                    <button type="submit" class="btn btn-success btn-sm rounded-0">Search</button>
                  </div>
                </form>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="min-width:280px;">Enquiry</th>
                      <th style="min-width:160px;">Buyer</th>
                      <th>Quantity</th>
                      <th style="min-width:110px;">Posted</th>
                      <th style="min-width:200px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($enquiries as $enquiry)
                    <tr>
                      <td>
                        <div class="eq-title">{{$enquiry->title}}</div>
                        <small class="text-muted">{{$enquiry->category?->name ?: 'Any category'}} · {{$enquiry->quotes_count}} {{\Illuminate\Support\Str::plural('quote',$enquiry->quotes_count)}}</small>
                        @if($enquiry->description)<div class="eq-desc">{{\Illuminate\Support\Str::limit($enquiry->description,300)}}</div>@endif
                        @if($enquiry->isRejected())<div class="eq-reason"><b>Rejected:</b> {{$enquiry->reject_reason}}</div>@endif
                      </td>
                      <td>
                        {{$enquiry->user?->name ?: '—'}}
                        @if($enquiry->user?->mobile)<br><small class="text-muted">{{$enquiry->user->mobile}}</small>@endif
                        @if($enquiry->user?->email)<br><small class="text-muted">{{$enquiry->user->email}}</small>@endif
                      </td>
                      <td>{{$enquiry->quantityLabel() ?: '—'}}</td>
                      <td>{{$enquiry->created_at?->format('d-m-Y')}}<br><small class="text-muted">{{$enquiry->created_at?->diffForHumans()}}</small></td>
                      <td>
                        @unless($enquiry->approval=='approved')
                        <form action="{{route('admin.enquiriesApprove',$enquiry->id)}}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        @endunless
                        @unless($enquiry->isRejected())
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="collapse" data-target="#reject{{$enquiry->id}}">Reject</button>
                        <div class="collapse mt-1 eq-reject" id="reject{{$enquiry->id}}">
                          <form action="{{route('admin.enquiriesReject',$enquiry->id)}}" method="post">
                            @csrf
                            <textarea name="reject_reason" rows="2" class="form-control mb-1" placeholder="Reason the buyer will see" required maxlength="1000"></textarea>
                            <button type="submit" class="btn btn-sm btn-danger">Reject enquiry</button>
                          </form>
                        </div>
                        @endunless
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">No {{strtolower(\App\Models\Enquiry::APPROVALS[$approval])}} enquiries.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              {{$enquiries->links('pagination')}}
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

@endsection
