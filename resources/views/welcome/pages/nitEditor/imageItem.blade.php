<div class="col-md-{{$item->grid_column}}" style="padding:5px;">
    <img @if ($item->class_name) class="{{$item->class_name}}" @endif src="{{asset($item->image())}}">
</div>