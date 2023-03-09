<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($items as $item)
            <li class="breadcrumb-item">
                @if ($item->href)
                    <a href="{{$item->href}}">{{$item->text}}</a>
                @else
                    <span>{{$item->text}}</span>
                @endif
            </li>
        @endforeach
        <!--<li class="breadcrumb-item active">Страны</li>-->
    </ol>
</nav>
