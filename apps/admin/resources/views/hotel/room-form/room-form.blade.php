@extends('layouts.main')

@section('styles')
    @vite('resources/views/hotel/room-form/room-form.scss')
@endsection

@section('scripts')
    {!! Js::variables([
        'bedTypes' => $bedTypes,
    ]) !!}

    @vite('resources/views/hotel/room-form/room-form.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body">
        <form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}">
            <div class="card card-form">
                <div class="card-body">
                    <div class="form-fields">
                        {!! $form !!}
                    </div>
                </div>
            </div>

            <div class="card card-form mt-4">
                <div class="card-header"><h5>Описание</h5></div>

                <div class="textarea-wrapper">
                    <textarea name="text[ru]" id="room-text-textarea" style="visibility: hidden">{!! $text ?? '' !!}</textarea>
                </div>
            </div>

            <div class="card card-form mt-4">
                <div class="card-header"><h5>Спальные места</h5></div>

                <div class="card-body room-beds" id="room-beds"></div>
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
@endsection
