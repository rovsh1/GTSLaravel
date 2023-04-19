@extends('layouts/main')

@section('head')
    @vite('resources/js/pages/hotel/rooms.ts')
    @vite('resources/sass/pages/hotel/rooms.scss')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body rooms-cards" id="hotel-rooms">
        @foreach($rooms as $room)
            <div class="card room" data-id="{{ $room->id }}">
                <div class="edit-info">
                        <?php
//                                    $flags = $room->data_flags;
//                                    foreach (\HOTEL_ROOM_DATA_FLAG::getLabels() as $flag => $l) {
//                                        echo '<div data-flag="'  . $flag . '" class="' . ($flags & $flag ? 'success' : 'error') . '">' . $l . '</div>';
//                                    }
                        ?>
                </div>
                <div class="image">
                    <x-file-image :file="null"/>
                    <div class="name">{{ $room }}</div>
                </div>
                <div class="body">
                    <div class="type">
                        {{ $room->type_name }} на <b>{{ $room->guests_number }}</b> {{ trans_choice('человека|человека|человек', $room->guests_number) }}
                        (<b>x{{ $room->rooms_number }}</b>)
                    </div>
                    <div class="buttons">
                        @if($editAllowed)
                            <a href="{{ route('hotels.rooms.edit', [$hotel, $room]) }}" class="btn btn-light">Редакировать</a>
                        @endif
                        <a href="#" class="btn btn-light">Фотографии</a>
                    </div>
                </div>
            </div>
        @endforeach

        @if($createUrl)
            <a class="card new" href="{{ $createUrl }}">
                <div class="btn">
                    <i class="icon">add</i>
                    Добавить номер
                </div>
            </a>
        @endif
    </div>
@endsection
