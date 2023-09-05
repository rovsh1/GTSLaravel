<div class="sitemap-wrapper">
    <div class="sitemap">
        <div class="categories" id="sitemap-categories">
            @foreach($categories as $category)
                <div data-category="{{ $category->key }}" class="item{{ $sitemap->isCurrent($category) ? ' current' : '' }}">
                    <a href="#" title="{{ $category->title }}">
                        <x-icon :key="$category->key"/>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="main-wrapper">
            <div class="btn-sitemap-toggle-switch-wrapper">
                <div class="main" id="sitemap-categories-menus">
                    {!! Layout::sidebar() !!}
                    @foreach($categories as $category)
                        @if(!$sitemap->isCurrent($category))
                            <aside class="sidebar" data-category="{{ $category->key }}">
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
                        @endif
                    @endforeach
                </div>
                <div class="btn-sitemap-toggle-switch">
                    <x-icon key="chevron_right"/>
                </div>
            </div>
        </div>
    </div>
</div>
