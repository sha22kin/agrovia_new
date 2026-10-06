<div class="col-md-{{$item->grid_column}}" style="padding:5px;">
    <div @if ($item->class_name) class="{{$item->class_name}}" @endif>
    {!!$item->content!!} 
    </div>
</div>