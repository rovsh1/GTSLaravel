@extends('default.form.form')

@section('scripts')
    {!! Js::variables([
            'hotelID' => $hotel->id,
            'seasonID' => $season->id,
    ], 'hotel-season-edit') !!}

    @vite('resources/views/hotel/season/edit.ts')
@endsection
