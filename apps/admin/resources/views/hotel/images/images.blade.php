@extends('layouts.main')

@section('scripts')
    @vite('resources/views/hotel/images/images.ts')
@endsection

@section('content')
    <div
        id="hotel-images"
        data-vue-initial="{{ json_encode(['hotelID' => $hotel->id]) }}"
    ></div>
@endsection
