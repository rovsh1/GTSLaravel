@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        @if (isset($createUrl) && $createUrl)
            <a href="{{ $createUrl }}" class="btn btn-add">
                <x-icon key="add"/>
                Создать</a>
        @endif
        <div class="flex-grow-1"></div>

        <form method="get" class="grid-filters">
            @if ($searchForm)
                <button type="button" class="btn btn-white" id="btn-grid-filters">
                    <x-icon key="filter"/>
                </button>
            @endif

            <div class="quicksearch-wrapper">
                {!! $quicksearch !!}
                <button class="icon" type="submit" title="Найти">search</button>
            </div>

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
        <div class="card card-grid">{!! $grid  !!}</div>
    </div>
@endsection
