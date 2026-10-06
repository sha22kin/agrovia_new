<div class="col-md-{{$item->grid_column}}" style="padding:5px;">
    <{{$item->name?:'h1'}}  @if ($item->class_name) class="{{$item->class_name}}" @endif >
    {!!$item->content!!} 
    </{{$item->name?:'h1'}}>
</div>