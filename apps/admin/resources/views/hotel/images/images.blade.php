@extends('layouts.main')

@section('scripts')
    {!! Js::variables([
        'hotelID' => $hotel->id,
    ]) !!}

    @vite('resources/views/hotel/images/images.ts')
@endsection

@section('content')
    <div id="hotel-images"></div>
@endsection
