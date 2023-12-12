@extends('default.form.form')

@section('scripts')
    {!! Js::variables([
            'hotelID' => $hotel->id,
            'seasonID' => $season->id,
    ]) !!}

    @vite('resources/views/hotel/season/edit.ts')
@endsection
