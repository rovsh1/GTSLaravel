<div class="sitemap-category-menu">
    @if(count($category->items()) > 0)
        <nav>
            @foreach($category->items() as $item)
                <a href="{{ $item->url }}" class="{{ $sitemap->isCurrent($item) ? 'current' : '' }}">{{ $item->text }}</a>
            @endforeach
        </nav>
    @endif

    @foreach($category->groups() as $group)
        <div class="group">
            <div class="group-title">{{ $group->title }}</div>
            <div class="group-items">
                <nav>
                    @foreach($group->items() as $item)
                        <a href="{{ $item->url }}" class="{{ $sitemap->isCurrent($item) ? 'current' : '' }}">{{ $item->text }}</a>
                    @endforeach
                </nav>
            </div>
        </div>
    @endforeach
</div>
