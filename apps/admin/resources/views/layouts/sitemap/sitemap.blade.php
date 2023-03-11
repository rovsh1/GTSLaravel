<div class="sitemap-wrapper">
    <div class="sitemap">
        <div class="categories" id="sitemap-categories">
            @foreach($categories as $category)
                <div data-category="{{ $category->key }}" class="item{{ $sitemap->isCurrent($category) ? ' current' : '' }}">
                    <a href="#" title="{{ $category->title }}">{!! Icon::category($category->key) !!}</a>
                </div>
            @endforeach
        </div>

        <div class="main-wrapper">
            <div class="main" id="sitemap-categories-menus">
                @foreach($categories as $category)
                    <div class="category-menu-wrapper" style="{{ $sitemap->isCurrent($category) ? '' : 'display:none' }}" data-category="{{ $category->key }}">
                        <div class="title">{{ $category->title }}</div>
                        @include("/layouts/sitemap/category-menu")
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
