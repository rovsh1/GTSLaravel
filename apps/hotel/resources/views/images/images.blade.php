@extends('layouts.main')

@section('scripts')
    {!! Js::variables([
        'hotelID' => $hotel->id,
    ]) !!}

    @vite('resources/views/images/images.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div id="hotel-images"></div>
@endsection
