<div class="sidebar-menu">
    @if(count($menu->items()) > 0)
        <nav>
            @foreach($menu->items() as $item)
                <a href="{{ $item->url }}" class="{{ $menu->isCurrent($item) ? 'current' : '' }}">
                    <x-icon :key="$item->icon"/>
                    {{ $item->text }}
                </a>
            @endforeach
        </nav>
    @endif

    @foreach($menu->groups() as $group)
        <div class="group">
            <div class="group-title">{{ $group->title }}</div>
            <div class="group-items">
                <nav>
                    @foreach($group->items() as $item)
                        <a href="{{ $item->url }}" class="{{ $menu->isCurrent($item) ? 'current' : '' }}">
                            <x-icon :key="$item->icon"/>
                            {{ $item->text }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    @endforeach
</div>
