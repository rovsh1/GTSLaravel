@extends('layouts/main')

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
            @include('_partials/components/contacts-card', ['collapsable' => true])
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Дополнительные параметры">
                Дополнительные параметры
            </x-ui.card>
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Сотрудники (count)">
                Дополнительные параметры
            </x-ui.card>
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Примечание">
                Дополнительные параметры
            </x-ui.card>
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Администраторы (count)">
                Дополнительные параметры
            </x-ui.card>
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Объекты и достопримечательности (count)">
                Дополнительные параметры
            </x-ui.card>
        </div>
    </div>
@endsection
