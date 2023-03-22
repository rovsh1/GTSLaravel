@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <x-ui.add-button :url="$createUrl ?? null"/>
        <div class="flex-grow-1"></div>

        <form method="get" class="grid-filters">
            @if ($searchForm)
                <button type="button" class="btn btn-white" id="btn-grid-filters">
                    <x-icon key="filter"/>
                </button>
            @endif

            @if($quicksearch)
                <div class="quicksearch-wrapper">
                    {!! $quicksearch !!}
                    <button class="icon" type="submit" title="Найти">search</button>
                </div>
            @endif

            <div class="search-popup-wrapper" id="grid-filters-popup">
                <div class="search-popup shadow rounded">
                    {!! $searchForm !!}
                    <div class="buttons">
                        <button class="btn btn-primary" type="submit">Найти</button>
                        <button class="btn btn-cancel ms-2" type="reset">Сбросить</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="content-body">
        <div class="card card-grid">
            <div class="card-body">{!! $grid  !!}</div>
        </div>
    </div>
@endsection
