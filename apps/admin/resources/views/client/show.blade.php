@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel/show/show.scss')
@endsection

@section('scripts')
    @vite('resources/views/client/show.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.actions-menu :$editUrl :$deleteUrl/>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form mt-3">
            <div class="card-body">
                {!! $params !!}
            </div>
        </div>

        <div class="mt-3">
            @include('_partials.components.contacts-card')
        </div>
    </div>

@endsection
