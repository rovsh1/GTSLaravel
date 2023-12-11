@extends('layouts.main')

@section('styles')
    {!! Js::variables([
        'hotelID' => $hotel->id,
        'seasons' => $seasons,
        'priceRates' => $priceRates,
        'rooms' => $rooms
    ], 'hotel-prices') !!}

    @vite('resources/views/hotel/prices/prices.scss')
@endsection

@section('scripts')
    @vite('resources/views/hotel/prices/prices.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>
    <div class="content-body" id="hotel-prices">
    </div>
@endsection
