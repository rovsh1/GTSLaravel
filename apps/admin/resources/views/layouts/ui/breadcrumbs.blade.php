<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($items as $item)
            <li class="breadcrumb-item">
                <a href="{{$item->href}}">{{$item->text}}</a>
            </li>
        @endforeach
        <!--<li class="breadcrumb-item active">Страны</li>-->
    </ol>
</nav>
