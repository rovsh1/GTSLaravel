@extends('layouts.main')

@section('styles')
    @vite('resources/views/show/show.scss')
@endsection

@section('scripts')
    @vite('resources/views/show/show.ts')
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
            @include('show._partials.services-card', ['collapsable' => true])
        </div>

        <div class="mt-3">
            <x-ui.card :collapsable="true" header="Тестовое описание">
                @if($notesUrl)
                    <x-slot:header-controls>
                        <a href="{{ $notesUrl }}" class="btn btn-add" id="btn-notes-edit">
                            <x-icon key="edit"/>
                            Редактировать
                        </a>
                    </x-slot:header-controls>
                @endif
                
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach(['ru', 'uz', 'en'] as $index => $lang)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="description-{{$lang}}-tab" data-bs-toggle="tab" data-bs-target="#description-{{$lang}}" type="button">
                                <img width="20px" src="{{ asset('images/flag/'. $lang .'.svg') }}" alt="{{$lang}}">
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach(['ru', 'uz', 'en'] as $index => $lang)
                        <div class="tab-pane show mt-3 {{ $index === 0 ? 'active' : '' }}" id="description-{{$lang}}" role="tabpanel">
                            @if($model->text)
                                {!! $model->text !!}
                            @else
                                <i class="empty">Отсутствует</i>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
