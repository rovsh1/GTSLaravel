<aside class="sidebar">
    <div class="header-logo"></div>

    <ul class="list-unstyled ps-0">
        @foreach($groups as $group)
            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                    {{ $group->title }}
                </button>
                <div class="collapse" id="home-collapse" style="">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">
                        @foreach($group->items as $item)
                            <li><a href="{{ $item->href }}" class="link-dark d-inline-flex text-decoration-none rounded">{{ $item->text }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endforeach
        <li class="border-top my-3"></li>
    </ul>
</aside>
