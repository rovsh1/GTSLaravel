@extends('layouts.main')

@section('styles')
    @vite('resources/views/supplier/show.scss')
@endsection

@section('scripts')
    @vite('resources/views/supplier/show.js')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.actions-menu :$editUrl :$deleteUrl/>
        <div class="flex-grow-1"></div>

    </div>

    <div class="content-body">
        <x-ui.card header="Основные данные">
            {!! $params !!}
        </x-ui.card>

        <div class="mt-3">
            @include('_partials.components.contacts-card')
        </div>
    </div>
@endsection
