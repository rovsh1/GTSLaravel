@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel/prices/prices.scss')
@endsection

@section('scripts')
    @vite('resources/views/hotel/prices/prices.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body rooms-cards" id="hotel-prices">
        @foreach($rooms as $room)
            <div class="card room" data-id="{{ $room->id }}">
                <div class="card-header">
                    {{ $room->display_name }}
                </div>
                <div class="card-body">

                </div>
            </div>
        @endforeach
    </div>
@endsection
