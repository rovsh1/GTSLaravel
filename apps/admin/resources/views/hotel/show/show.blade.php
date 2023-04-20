@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel/show/show.scss')
@endsection

@section('scripts')
    @vite('resources/views/hotel/show/show.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.actions-menu :$editUrl :$deleteUrl/>
        <div class="flex-grow-1"></div>

        {!! Layout::actions() !!}
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                {!! $params !!}
            </div>
        </div>

        <div class="mt-3">
            @include('_partials.components.contacts-card', ['collapsable' => true])
        </div>

        <div class="mt-3">
            @include('hotel._partials.services-card', ['collapsable' => true])
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Примечание">
                @if($notesUrl)
                    <x-slot:header-controls>
                        <a href="{{ $notesUrl }}" class="btn btn-add" id="btn-notes-edit">
                            <x-icon key="edit"/>
                            Редактировать
                        </a>
                    </x-slot:header-controls>
                @endif

                @if($model->text)
                    {{ $model->text }}
                @else
                    <i class="empty">Отсутствует</i>
                @endif
            </x-ui.card>
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Администраторы ({{$model->users->count()}})">
                {!! $usersGrid !!}
            </x-ui.card>
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Объекты и достопримечательности ({{$model->landmarks->count()}})">
                @if($notesUrl)
                    <x-slot:header-controls>
                        <button id="btn-hotel-landmarks" class="btn btn-add" data-url="{{$landmarkUrl}}">
                            <x-icon key="add"/>
                            Добавить объект
                        </button>
                    </x-slot:header-controls>
                @endif

                {!! $landmarkGrid !!}
            </x-ui.card>
        </div>
    </div>
@endsection
