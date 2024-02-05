@extends('layouts.main')

@section('styles')
    @vite('resources/views/default/form/form.scss')
@endsection

@section('scripts')
    @vite('resources/views/profile/password/form.ts')
@endsection

@section('content')
    <x-ui.content-title/>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                <form method="post" data-title="{{ $title }}" class="settings-form {{ $cls ?? '' }}">
                    {!! isset($description) ? '<p>' . $description . '</p>' : '' !!}

                    <div class="fields-wrap">
                        {!! $form !!}
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">@lang('Сохранить')</button>
                        @if(isset($cancelUrl))
                            <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

