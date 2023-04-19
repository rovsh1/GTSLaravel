@extends('layouts/main')

@section('head')
    @vite('resources/js/pages/hotel/prices.ts')
    @vite('resources/sass/pages/hotel/prices.scss')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body rooms-cards" id="hotel-prices">
        @foreach($rooms as $room)
            <div class="card room" data-id="{{ $room->id }}">
                <div class="card-header">
                    {{ $room->name }}
                </div>
                <div class="card-body">

                </div>
            </div>
        @endforeach
    </div>
@endsection
