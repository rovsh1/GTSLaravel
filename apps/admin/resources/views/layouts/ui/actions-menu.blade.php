<div class="grid-actions float-end">
    @if(count($items) > 1)
        <nav>
            @foreach($items as $item)
                <a href="{{ $item->url }}">{{ $item->text }}</a>
            @endforeach
        </nav>
    @else
        <a href="{{ $items[0]->url }}" class="btn btn-primary">{{ $items[0]->text }}</a>
    @endif
</div>
