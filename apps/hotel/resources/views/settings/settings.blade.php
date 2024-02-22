@extends('layouts.main')

@section('styles')
    @vite('resources/views/settings/settings.scss')
@endsection

@section('scripts')
    @vite('resources/views/settings/settings.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div id="residence-conditions"></div>
        <div class="mt-3" id="cancellation-conditions"></div>
    </div>
@endsection
