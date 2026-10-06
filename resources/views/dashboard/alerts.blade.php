@foreach(['success'=>'success','error'=>'danger','info'=>'info'] as $key=>$class)
@if(session($key))
<div class="alert alert-{{$class}} alert-dismissible fade show" role="alert">
  {{session($key)}}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@endforeach
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  Please check the highlighted fields.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
