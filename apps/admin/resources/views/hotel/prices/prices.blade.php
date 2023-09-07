@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel/prices/prices.scss')
@endsection

@section('head-end')
    <script >
      window['view-initial-data-hotel-prices'] = {{ Js::from([
            'hotelID' => $hotel->id,
            'seasons' => $seasons,
            'priceRates' => $priceRates,
            'rooms' => $rooms
      ]) }}
    </script>
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
