@extends('layouts.main')

@section('scripts')
    @vite('resources/views/mail/template/form.scss')
    @vite('resources/views/mail/template/form.ts')
@endsection

@section('content')
    {!! ContentTitle::default() !!}

    <div class="content-body">
        <form action="{{ $form->action }}" method="{{ strtoupper($form->method) === 'GET' ? 'GET' : 'POST' }}"
        >
            <div class="card card-form">
                <div class="card-body">
                    <div class="form-group">{!! $form !!}</div>

                </div>
            </div>

            <h5 class="mt-4 mb-3">Текст письма</h5>
            <div class="textarea-wrapper">
                <textarea name="data[body]" id="mail-body-textarea"
                          style="visibility: hidden">{!! $body ?? '' !!}</textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Сохранить' }}</button>
                @if(isset($cancelUrl))
                    <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                @endif
                <div class="spacer"></div>
                <x-form.delete-button :url="$deleteUrl ?? null"/>
            </div>
        </form>
    </div>
@endsection
