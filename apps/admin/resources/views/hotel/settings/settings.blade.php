@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel/settings/settings.scss')
@endsection

@section('scripts')
    @vite('resources/views/hotel/settings/settings.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div id="residence-conditions"></div>

        <div class="mt-3" id="cancellation-conditions"></div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Правила отеля" class="card-grid">
                <x-slot:header-controls>
                    <a href="#" class="btn btn-add" id="btn-rules-add" data-url="{{ $createRuleUrl }}">
                        <x-icon key="add"/>
                        Добавить правило
                    </a>
                </x-slot:header-controls>

                {!! $rulesGrid !!}
            </x-ui.card>
        </div>

        <h5 class="mt-3">Скидки и наценки</h5>

        <div class="mt-3" id="markup-conditions"></div>

        <div class="mt-3" id="room-markups">
            <x-ui.card :collapsable="true" header="Номера" class="card-grid">
                @include('hotel.settings.components.room-markups')
            </x-ui.card>
        </div>

@endsection
