@extends('layouts.main')

@section('styles')
    @vite('resources/views/booking/hotel/timeline/timeline.scss')
@endsection

@section('scripts')
    @vite('resources/views/booking/hotel/timeline/timeline.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        @if(count($history) > 0)
            <div class="card card-form disable-border">
                <div class="card-body">
                    <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                        @foreach($history as $event)
                            <div class="vertical-timeline-item vertical-timeline-element item group-{{ strtolower($event->event) }}">
                                <div>
                                    <div class="vertical-timeline-element-icon">
                                        <i class="badge badge-dot badge-dot-xl bg-{{ $event->color != null ? $event->color : 'primary' }}"> </i>
                                    </div>
                                    <div class="vertical-timeline-element-content">
                                        <div class="alert alert-secondary d-flex justify-content-between align-items-center gap-2" role="alert">
                                            <div class="description">
                                                {!! $event->description !!}
                                            </div>
                                            <div class="actions d-flex gap-2">
                                                <div class="payload d-flex align-items-center">
                                                    <div class="btn-data-content"
                                                        data-modal-title="Опиание"
                                                        data-content="{{ json_encode($event->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}">
                                                        <i class="icon">description</i>
                                                    </div>
                                                </div>
                                                <div class="context d-flex align-items-center">
                                                    <div class="btn-data-content"
                                                        data-modal-title="Информация"
                                                        data-content="{{ json_encode($event->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}">
                                                        <i class="icon">info</i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="vertical-timeline-element-date">{{ Format::date($event->createdAt, 'datetime') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body"><div class="grid-empty-text">Записи отсутствуют</div></div>
            </div>
        @endif
    </div>
@endsection
