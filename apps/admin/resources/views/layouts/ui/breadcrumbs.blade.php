<div class="breadcrumb-wrapper">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <!--<a href="/">
                <x-icon key="home"/>
            </a>-->
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
</div>
