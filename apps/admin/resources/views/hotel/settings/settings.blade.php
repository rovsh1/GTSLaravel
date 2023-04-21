@extends('layouts.main')

@section('scripts')
    @vite('resources/views/hotel/settings/settings.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                <div id="hotel-show">
                    <div id="hotel-settings"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
