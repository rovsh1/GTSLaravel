<aside class="sidebar">
    <div class="logo-wrapper">
        <button>
            <svg width="100%" height="100%" viewBox="0 0 24 24" fill="currentColor" preserveAspectRatio="xMidYMid meet" focusable="false">
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
            </svg>
        </button>
        <div class="logo"></div>
    </div>

    @if ($current)
        <div class="sidebar-header">
            <i class="icon">{{ $current->category->icon }}</i>
            <div class="title">{{ $current->category->title }}</div>
        </div>
    @endif

    <div class="main-menu-wrapper">
        <div class="main-menu">
            @if ($displayCategories)
                <div class="categories">
                    <div class="item">
                        <a href="" title="Dashboard"><span class="icon">home</span></a>
                    </div>
                    @foreach($categories as $category)
                        <div data-category="{{ $category->key }}" class="item{{ $sidebar->isCurrent($category) ? ' current' : '' }}">
                            <a href="#" title="{{ $category->title }}"><span class="icon">{{ $category->icon }}</span></a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="main-wrapper">
                <div class="main">
                    @foreach($categories as $category)
                        <div data-category="{{ $category->key }}" class="menu-wrapper">
                            <div class="menu">
                                <nav>
                                    @foreach($category->items() as $item)
                                        <a href="{{ $item->url }}" class="{{ $sidebar->isCurrent($item) ? 'current' : '' }}">{{ $item->text }}</a>
                                    @endforeach
                                </nav>

                                @foreach($category->groups() as $group)
                                    <div class="group">
                                        @if ($group->title)
                                            <div class="group-title">{{ $group->title }}</div>
                                        @endif
                                        <div class="group-items">
                                            <nav>
                                                @foreach($group->items() as $item)
                                                    <a href="{{ $item->url }}" class="{{ $sidebar->isCurrent($item) ? 'current' : '' }}">{{ $item->text }}</a>
                                                @endforeach
                                            </nav>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="submenu">
            </div>
        </div>
    </div>

    <div class="footer-menu-wrapper">

    </div>
</aside>
