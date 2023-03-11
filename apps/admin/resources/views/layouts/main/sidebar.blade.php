<aside class="sidebar">
    <div class="sidebar-header">
        {!! Icon::category($category->key) !!}
        <div class="title">{{ $category->title }}</div>
    </div>

    <div class="menu-wrapper">
        @include("/layouts/sitemap/category-menu")
    </div>

    <div class="footer-menu-wrapper">

    </div>
</aside>
