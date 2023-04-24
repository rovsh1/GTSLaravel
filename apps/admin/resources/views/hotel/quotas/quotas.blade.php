@extends('layouts.main')

@section('scripts')
    @vite('resources/views/hotel/quotas/quotas.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body pb-0">
                <div id="hotel-show">
                    <div id="quotas-tables"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
