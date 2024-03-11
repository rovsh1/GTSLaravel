@extends('layouts.main')

@section('styles')
    @vite('resources/views/rooms/rooms.scss')
@endsection

@section('scripts')
    @vite('resources/views/rooms/rooms.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body rooms-cards" id="hotel-rooms">
        @foreach($rooms as $room)
            <div class="card room" data-id="{{ $room->id }}">
                <div class="image">
                    <x-file-image :file="$room->main_image"/>
                    <div class="name">{{ $room->name }}</div>
                </div>
                <div class="body">
                    <div class="type">
                        {{ $room->type_name }} на
                        <b>{{ $room->guests_count }}</b> {{ trans_choice('человека|человека|человек', $room->guests_number) }}
                        (<b>x{{ $room->rooms_number }}</b>)
                    </div>
                    <div class="usability-wrapper">
                        <div class="usability">
                            <div class="usability-height-checker">
                                @foreach($room->usabilities as $usability)
                                    <span class="badge badge-success">{{ $usability->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="usability-expand">
                            <i class="material-symbols-outlined">expand_more</i>
                        </div>
                    </div>
                    <div class="buttons">
                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-light">Описание</a>
                        <a href="{{ route('images.index', ['room_id' => $room->id]) }}" class="btn btn-light">Фотографии</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
