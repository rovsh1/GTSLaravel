@extends('layouts.main')

@section('styles')
    @vite('resources/views/service-provider/show.scss')
@endsection

@section('scripts')
    @vite('resources/views/service-provider/show.js')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.actions-menu :$editUrl :$deleteUrl/>
        <div class="flex-grow-1"></div>

    </div>

    <div class="content-body">
        @include('_partials.components.contacts-card')

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Основные данные">

            </x-ui.card>
        </div>
    </div>
@endsection
