@extends('layouts.main')

@section('styles')
    @vite('resources/views/room-form/room-form.scss')
@endsection

@section('scripts')
    @vite('resources/views/room-form/room-form.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body">
        <form class="retry-submit-lock" method="POST">
            <div class="card card-form">
                <div class="card-header"><h5>Описание</h5></div>
                <div class="textarea-wrapper">
                    @foreach(['ru', 'en', 'uz'] as $lang)
                        <textarea name="notes[{{$lang}}]" id="room-text-textarea" style="visibility: hidden">{!! $values[$lang] ?? '' !!}</textarea>
                    @endforeach
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">{{ $submitText ?? 'Сохранить' }}</button>
                @if(isset($cancelUrl))
                    <a href="{{ $cancelUrl }}" class="btn btn-cancel">Отмена</a>
                @endif
            </div>
        </form>
    </div>
@endsection
