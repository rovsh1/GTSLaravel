@extends('layouts.main')

@section('styles')
    {!! Js::variables([
        'hotelID' => $hotel->id,
        'isTravelineIntegrationEnabled' => (bool)$hotel->is_traveline_integration_enabled,
        'seasons' => $seasons,
        'priceRates' => $priceRates,
        'rooms' => $rooms
    ]) !!}

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
