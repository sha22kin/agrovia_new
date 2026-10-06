@foreach($datas as $i=>$data)
<div class="container">
    <div class="row" style="margin:0 -5px;">
        @foreach($data->subItems as $item)
        @if($item->item_type=='paragraph')
            @include(welcomeTheme().'pages.nitEditor.paragraphItem',['item'=>$item])
        @elseif($item->item_type=='heading')
            @include(welcomeTheme().'pages.nitEditor.headingItem',['item'=>$item])
        @elseif($item->item_type=='image')
            @include(welcomeTheme().'pages.nitEditor.imageItem',['item'=>$item])
        @elseif($item->item_type=='gridColumn')
            @include(welcomeTheme().'pages.nitEditor.gridColumnItem',['item'=>$item])
        @endif
        @endforeach
    </div>
</div>
@endforeach