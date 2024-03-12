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
        <form class="retry-submit-lock" method="POST" action="{{route('rooms.update', $model)}}">
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
                    <div class="tab-pane show {{ $index === 0 ? 'active' : '' }}" id="description-{{$lang}}" role="tabpanel">
                        <div class="textarea-wrapper mt-2">
                            <textarea name="notes[{{$lang}}]" id="room-text-textarea-{{$lang}}" style="visibility: hidden">{!! $values[$lang] ?? '' !!}</textarea>
                        </div>
                    </div>
                @endforeach
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
