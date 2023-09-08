<aside class="sidebar current-category-sidebar" data-category="{{ $category->key }}">
    @if ($submenu)
        <button id="btn-sidebar-toggle" aria-label="Toggle sidebar menu">
            <x-icon key="arrow_back"/>
        </button>

        <div class="submenu-wrapper">
            <div class="sidebar-header">
                <div class="title-wrapper">{!! $submenu->title() !!}</div>
            </div>
            {!! $submenu->render() !!}
        </div>
    @endif

    <div class="main-menu-wrapper">
        <div class="sidebar-header">
            <x-icon :key="$category->key"/>
            <div class="title">{{ $category->title }}</div>
        </div>

        <div class="menu-wrapper">
            {!! $category->render() !!}
        </div>
    </div>
</aside>
