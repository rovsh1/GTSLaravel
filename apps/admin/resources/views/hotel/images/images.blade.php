@extends('layouts.main')

@section('scripts')
    @vite('resources/views/hotel/images/images.ts')
@endsection

@section('content')
    <div
        id="hotel-images"
        data-vue-initial="{{ json_encode(['hotel-id' => $hotel->id]) }}"
    ></div>
@endsection
