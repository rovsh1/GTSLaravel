@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>

        {!! Layout::actions() !!}
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <x-tab target="main" active="true">Основные</x-tab>
                    <x-tab target="rules">Правила</x-tab>
                </ul>
            </div>
            <div class="card-body">
                <form
                    class="tab-content"
                    action="{{ $form->action }}"
                    method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}"
                    enctype="multipart/form-data">
                    <div id="main" role="tabpanel" class="tab-pane fade show active" aria-labelledby="main-tab">
                        <div class="form-group">{!! $form !!}</div>
                    </div>
                    <div id="rules" role="tabpanel" class="tab-pane fade" aria-labelledby="rules-tab">
                        <div class="d-flex align-items-start">
                            <div class="nav flex-column nav-pills me-3" id="permissions-tabs" aria-orientation="vertical">
                                @foreach($categories as $key)
                                    <button class="nav-link{{ $key === $default ? ' active' : '' }}"
                                            type="button"
                                            role="tab"
                                            data-category="{{ $key }}"
                                            aria-selected="{{ $key === $default ? 'true' : 'false' }}">{{ __('category.' . $key) }}</button>
                                @endforeach
                            </div>
                            <div class="permissions-control-wrapper" id="permissions"></div>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Сохранить' }}</button>
                        @if(isset($cancelUrl))
                            <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                        @endif
                        <div class="spacer"></div>
                        @if(isset($deleteUrl) && $deleteUrl)
                            <a href="#" data-url="{{ $deleteUrl }}" class="btn btn-delete">
                                <x-icon key="delete"/>
                                Удалить</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
