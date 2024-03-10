@extends('layouts.main')

@section('scripts')
    @vite('resources/views/hotel/notes/notes.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body">
        <form method="POST" class="htmleditor-form retry-submit-lock">
            <div class="textarea-wrapper">
                @foreach($values as $lang => $value)
                    <textarea name="notes[{{$lang}}]" id="hotel-notes-textarea" style="visibility: hidden">{!! $value ?? '' !!}</textarea>
                @endforeach
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ $cancelUrl ?? '' }}" class="btn btn-cancel">Отмена</a>
            </div>
        </form>
    </div>
@endsection
