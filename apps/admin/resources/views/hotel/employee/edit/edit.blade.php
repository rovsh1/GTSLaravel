@extends('layouts.main')

@section('scripts')
    @vite('resources/views/hotel/employee/edit/edit.ts')
@endsection

@section('content')
    <x-ui.content-title :addBtnUrl="$createUrl" addBtnText="Добавить контакт"/>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <x-ui.tab target="main" active="true">Основные</x-ui.tab>
                    <x-ui.tab target="contacts">Контакты</x-ui.tab>
                </ul>
            </div>
            <div class="card-body">
                <form
                    class="tab-content"
                    action="{{ $form->action }}"
                    method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}"
                    enctype="multipart/form-data"
                >
                    <div id="main" role="tabpanel" class="tab-pane fade show active" aria-labelledby="main-tab">
                        <div class="form-group">{!! $form !!}</div>
                    </div>

                    <div id="contacts" role="tabpanel" class="tab-pane fade" aria-labelledby="rules-tab" data-route="{{$contactsUrl}}">
                        @include('_partials.components.contacts-table')
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary retry-submit-lock">{{ $submitText ?? 'Сохранить' }}</button>
                        @if(isset($cancelUrl))
                            <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                        @endif
                        <div class="spacer"></div>
                        <x-form.delete-button :url="$deleteUrl ?? null"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
