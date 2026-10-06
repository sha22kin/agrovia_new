{{-- Shared by the buyer and seller dashboards (rendered by dashboard/page.blade.php). Needs $role. --}}
@php
  $byType = $documents->groupBy('type');
  $others = $documents->where('type',\App\Models\BusinessDocument::OTHER);
@endphp
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
  <h5 class="fw-bold mb-0">Business Information</h5>
  @if($summary['businessVerified'])
  <span class="av-verified-badge" style="background:var(--av-green-100);color:var(--av-green-700);border-color:var(--av-green-100);"><i class="fa-solid fa-circle-check"></i> Business Verified</span>
  @else
  <span class="av-verified-badge" style="background:#eef2ef;color:var(--av-muted);border-color:#eef2ef;"><i class="fa-regular fa-circle-xmark"></i> Not verified yet</span>
  @endif
</div>
<p class="text-secondary" style="font-size:.85rem;">{{$role == 'seller' ? 'Keep your business details accurate — buyers use this to decide whether to trust and order from you.' : 'Keep your business details accurate — sellers use this to confirm bulk order eligibility.'}}</p>

<div class="row">
  <div class="col-lg-8">
    <div class="av-card mt-0">
      <div class="av-section-title"><i class="fa-solid fa-building"></i> Company Details</div>
      <form method="POST" action="{{route($role.'.businessInfo')}}">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="av-form-label">Legal Business Name</label>
            <input type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{old('company_name',$user->company_name)}}" maxlength="200">
            @error('company_name')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="av-form-label">Business Type</label>
            <select class="form-select @error('business_type') is-invalid @enderror" name="business_type">
              <option value="">Select type</option>
              @foreach(\App\Models\BusinessProfile::TYPES as $option)
              <option value="{{$option}}" {{old('business_type',$business->business_type)==$option?'selected':''}}>{{$option}}</option>
              @endforeach
            </select>
            @error('business_type')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="av-form-label">Trade License No.</label>
            <input type="text" class="form-control @error('license_no') is-invalid @enderror" name="license_no" value="{{old('license_no',$business->license_no)}}" maxlength="60">
            @error('license_no')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="av-form-label">TIN / BIN Number</label>
            <input type="text" class="form-control @error('tin_bin') is-invalid @enderror" name="tin_bin" value="{{old('tin_bin',$business->tin_bin)}}" maxlength="60">
            @error('tin_bin')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="av-form-label">Established Year</label>
            <input type="number" class="form-control @error('established_year') is-invalid @enderror" name="established_year" value="{{old('established_year',$business->established_year)}}" min="1900" max="{{date('Y')}}" placeholder="2018">
            @error('established_year')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="av-form-label">Employees</label>
            <select class="form-select @error('employees') is-invalid @enderror" name="employees">
              <option value="">Select range</option>
              @foreach(\App\Models\BusinessProfile::EMPLOYEES as $option)
              <option value="{{$option}}" {{old('employees',$business->employees)==$option?'selected':''}}>{{$option}}</option>
              @endforeach
            </select>
            @error('employees')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-12">
            <label class="av-form-label">Business Address</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{old('address',$business->address)}}" maxlength="191" placeholder="Road, bazar, upazila, district">
            @error('address')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-12">
            <label class="av-form-label">Areas of Operation</label>
            <input type="text" class="form-control @error('areas') is-invalid @enderror" name="areas" value="{{old('areas',$business->areas)}}" maxlength="300" placeholder="Bogura, Sirajganj, Natore (separate with commas)">
            @error('areas')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
          <div class="col-12">
            <label class="av-form-label">Business Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" maxlength="500">{{old('description',$user->profile)}}</textarea>
            @error('description')<div class="invalid-feedback">{{$message}}</div>@enderror
          </div>
        </div>
        <div class="d-flex gap-2 mt-3">
          <button type="submit" class="btn-av-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
          <a href="{{route($role.'.profileOverview')}}" class="btn-av-outline">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="av-mini-card mt-0">
      <div class="av-section-title"><i class="fa-solid fa-file-shield"></i> Verification Documents</div>

      @foreach(\App\Models\BusinessDocument::REQUIRED as $type)
      @php $document = $byType->get($type)?->last(); @endphp
      <div class="av-doc-status mb-2">
        <div class="overflow-hidden">
          <div class="fw-semibold" style="font-size:.83rem;">{{$type}}</div>
          @if($document)
          <div class="text-muted" style="font-size:.72rem;">Uploaded {{$document->created_at?->format('M d, Y')}}</div>
          @else
          <div class="text-muted" style="font-size:.72rem;">Not uploaded yet</div>
          @endif
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
          @if($document)
          @php [$label,$class,$icon] = $document->statusBadge(); @endphp
          <span class="{{$class}}"><i class="fa-solid {{$icon}} me-1"></i>{{$label}}</span>
          <a href="{{route($role.'.businessDocumentAction',['download',$document->id])}}" class="text-muted" title="Download"><i class="fa-solid fa-download"></i></a>
          @unless($document->isVerified())
          <form action="{{route($role.'.businessDocumentAction',['delete',$document->id])}}" method="POST" data-confirm="Delete this document?" data-confirm-ok="Yes, delete" data-confirm-danger>@csrf<button type="submit" class="av-post-stat" title="Delete"><i class="fa-regular fa-trash-can"></i></button></form>
          @endunless
          @else
          <span class="badge-soft-grey">Missing</span>
          @endif
        </div>
      </div>
      @endforeach

      @foreach($others as $document)
      @php [$label,$class,$icon] = $document->statusBadge(); @endphp
      <div class="av-doc-status mb-2">
        <div class="overflow-hidden">
          <div class="fw-semibold text-truncate" style="font-size:.83rem;">{{$document->original_name ?: 'Document'}}</div>
          <div class="text-muted" style="font-size:.72rem;">Other · Uploaded {{$document->created_at?->format('M d, Y')}}</div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
          <span class="{{$class}}"><i class="fa-solid {{$icon}} me-1"></i>{{$label}}</span>
          <a href="{{route($role.'.businessDocumentAction',['download',$document->id])}}" class="text-muted" title="Download"><i class="fa-solid fa-download"></i></a>
          @unless($document->isVerified())
          <form action="{{route($role.'.businessDocumentAction',['delete',$document->id])}}" method="POST" data-confirm="Delete this document?" data-confirm-ok="Yes, delete" data-confirm-danger>@csrf<button type="submit" class="av-post-stat" title="Delete"><i class="fa-regular fa-trash-can"></i></button></form>
          @endunless
        </div>
      </div>
      @endforeach

      <form action="{{route($role.'.businessDocumentAction','upload')}}" method="POST" enctype="multipart/form-data" class="av-doc-tile mt-3">
        @csrf
        <i class="fa-solid fa-cloud-arrow-up d-block mb-2"></i>
        <div class="fw-semibold" style="font-size:.83rem;">Upload a document</div>
        <div class="text-muted mb-2" style="font-size:.72rem;">PDF, JPG or PNG, up to 5MB</div>
        <select name="type" class="form-select form-select-sm mb-2 @error('type') is-invalid @enderror" required>
          @foreach(\App\Models\BusinessDocument::TYPES as $type)
          <option value="{{$type}}" {{old('type')==$type?'selected':''}}>{{$type}}</option>
          @endforeach
        </select>
        <input type="file" name="document" class="form-control form-control-sm mb-2 @error('document') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png" required>
        @error('type')<div class="invalid-feedback d-block mb-2">{{$message}}</div>@enderror
        @error('document')<div class="invalid-feedback d-block mb-2">{{$message}}</div>@enderror
        <button type="submit" class="btn-av-outline btn-sm">Upload</button>
      </form>
    </div>
  </div>
</div>

