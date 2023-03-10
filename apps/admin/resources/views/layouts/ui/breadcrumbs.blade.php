<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="currentColor">
                    <path d="M6 19h3.7v-5.875h4.6V19H18v-9l-6-4.55L6 10Zm-1 1V9.5l7-5.3 7 5.3V20h-5.7v-5.875h-2.6V20Zm7-7.775Z"/>
                </svg>
            </a>
        </li>
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
