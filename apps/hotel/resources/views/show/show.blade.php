@extends('layouts.main')

@section('styles')
    @vite('resources/views/show/show.scss')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form mt-3">
            <div class="card-body">
                {!! $params !!}
            </div>
        </div>

        <div class="mt-3">
            @include('show.services-card', ['collapsable' => true])
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Примечание">
                @if($model->text)
                    {!! $model->text !!}
                @else
                    <i class="empty">Отсутствует</i>
                @endif
            </x-ui.card>
        </div>
    </div>
@endsection
