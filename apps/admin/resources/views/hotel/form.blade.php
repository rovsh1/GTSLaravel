@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>

        {!! Layout::actions() !!}
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                <form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}"
                      enctype="multipart/form-data">
                    <div class="form-group d-flex">
                        <div class="form-fields col-6">
                            {!! $form !!}
                        </div>
                        <div id="map" class="col-6" style="margin-left: 1rem"></div>
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
