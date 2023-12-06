@extends('layouts.main')

@section('styles')
    @vite('resources/views/booking/hotel/timeline/timeline.scss')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form disable-border">
            <div class="card-body">
                @foreach($history as $event)
                    <div class="item group-{{ strtolower($event->event) }}">
                        <time>{{ Format::date($event->createdAt, 'datetime') }}</time>
                        <div class="point"></div>
                        <div class="description">{!! $event->description !!}</div>
                        <div class="payload">
                            <div class="icon"
                                 title="{{ json_encode($event->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}">
                                description
                            </div>
                        </div>
                        <div class="context">
                            <div class="icon"
                                 title="{{ json_encode($event->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}">
                                info
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
