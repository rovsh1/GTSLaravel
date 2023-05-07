@extends('layouts.main')

@section('scripts')
    @vite('resources/views/hotel/quotas/quotas.ts')
@endsection

@section('content')
    <div
        id="hotel-quotas"
        data-vue-initial="{{ json_encode(['hotel-id' => $hotel->id]) }}"
    ></div>
@endsection
