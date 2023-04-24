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
        <div id="hotel-settings"></div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Правила отеля">
                <x-slot:header-controls>
                    <a href="#" class="btn btn-add" id="btn-rules-edit">
                        <x-icon key="edit"/>
                        Редактировать
                    </a>
                </x-slot:header-controls>

                123
            </x-ui.card>
        </div>

@endsection
