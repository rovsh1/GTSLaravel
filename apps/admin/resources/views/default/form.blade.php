@extends('layouts/main')

@section('content')
    <div class="card">
        <h5 class="card-header">
            {{ $title }}

            {!! Layout::actions() !!}
        </h5>
        <div class="card-body">
            <form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}" enctype="multipart/form-data">
                <div class="form-group">{!! $form !!}</div>

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
@endsection
