@extends('layouts.main')

@section('scripts')
    {!! Js::variables([
        'hotelID' => $hotel->id,
    ]) !!}

    @vite('resources/views/hotel/quotas/quotas.ts')
@endsection

@section('content')
    <div id="hotel-quotas"></div>
@endsection
