{{-- "Business Information" tab. Needs $user and $summary. --}}
<div class="av-card first">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="av-card-title"><i class="fa-solid fa-briefcase"></i> Business Information</div>
    <a href="{{$summary['role']=='buyer' ? route('buyer.businessInfo') : route($summary['role'].'.profile').'#business'}}" class="av-link-more">Edit <i class="fa-solid fa-arrow-right"></i></a>
  </div>
  <dl class="av-kv">
    <dt>Company / Business</dt><dd>{{$user->company_name ?: '—'}}</dd>
    <dt>Business Type</dt><dd>{{$summary['businessType']}}</dd>
    <dt>Areas of Operation</dt><dd>{{$summary['areas'] ?: '—'}}</dd>
    @if($summary['role']=='seller')
    <dt>Total Land Area</dt><dd>{{$summary['landArea'] ?: '—'}}</dd>
    <dt>Main Crops / Products</dt><dd>{{$summary['mainCrops'] ?: '—'}}</dd>
    @endif
    <dt>Mobile</dt><dd>{{$user->mobile ?: '—'}}</dd>
    <dt>Email</dt><dd>{{$user->email ?: '—'}}</dd>
    <dt>Address</dt><dd>{{$user->address_line1 ?: '—'}}</dd>
    <dt>City / Thana</dt><dd>{{$user->cityN?->name ?: '—'}}</dd>
    <dt>District</dt><dd>{{$user->districtN?->name ?: '—'}}</dd>
    <dt>Postal Code</dt><dd>{{$user->postal_code ?: '—'}}</dd>
  </dl>
</div>
