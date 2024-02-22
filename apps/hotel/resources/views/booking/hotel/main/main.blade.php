@extends('layouts.main')

@section('styles')
    @vite('resources/views/booking/hotel/main/main.scss')
@endsection

@section('scripts')
    @vite('resources/views/booking/hotel/main/main.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <div class="flex-grow-1"></div>

        <form method="get" class="grid-filters">
            @if (!empty($searchForm))
                <button type="button" class="btn btn-white" id="btn-grid-filters">
                    <x-icon key="filter"/>
                    <span id="grid-filters-counter" class="position-absolute bg-danger border border-light rounded-circle filter-badge d-none">
                        <span class="badge-text">
                            0
                        </span>
                    </span>
                </button>
            @endif

            @if(!empty($quicksearch))
                <div class="quicksearch-wrapper">
                    {!! $quicksearch !!}
                    <button class="icon" type="submit" title="Найти">search</button>
                </div>
            @endif

            @if(!empty($searchForm))
                <div class="search-popup-wrapper" id="grid-filters-popup">
                    <div class="search-popup shadow rounded">
                        {!! $searchForm !!}
                        <div class="buttons">
                            <button class="btn btn-primary" type="submit">Найти</button>
                            <button class="btn btn-cancel ms-2" type="reset">Сбросить</button>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>

    <div class="content-body">
        <div class="card card-grid">
            <div class="card-body">{!! $grid  !!}</div>
        </div>
    </div>

    <div class="content-header">
        <div class="title">{{ $title2 }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-grid">
            <div class="card-body">{!! $grid2 !!}</div>
        </div>
    </div>

    <div class="content-header">
        <div class="title">{{ $title3 }}</div>
        <div class="flex-grow-1"></div>
    </div>

    <div class="content-body">
        <div class="card card-grid">
            <div class="card-body">{!! $grid3 !!}</div>
        </div>
    </div>
@endsection
