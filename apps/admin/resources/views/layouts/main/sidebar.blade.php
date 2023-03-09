<aside class="sidebar">
    <div class="logo-wrapper">
        <div class="logo"></div>
    </div>

    <div class="main-menu-wrapper">
        @foreach($sidebars as $sidebar)
            <div class="card">
                <div class="card-header">{{ $sidebar->title }}</div>
                <div class="card-body">
                    @foreach($sidebar->groups as $group)
                        <div class="card-header">{{ $group->title }}</div>
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">
                            @foreach($group->items as $item)
                                <li><a href="{{ $item->href }}" class="link-dark d-inline-flex text-decoration-none rounded">{{ $item->text }}</a></li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        @endforeach
        <li class="border-top my-3"></li>
    </div>

    <div class="footer-menu-wrapper">

    </div>
</aside>
