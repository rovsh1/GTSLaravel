@extends('layouts/main')

@section('content')
    <div class="card grid-card">
        <div class="card-header">
            {{ $title }}
            <form method="get" class="grid-filters">
                <button type="button" class="btn btn-light"><i class="icon-filter"></i></button>
                <div class="quicksearch-wrapper">
                    {!! $quicksearch !!}
                    <button class="icon-search" type="submit" title="Найти"></button>
                </div>
                <div class="search-popup-wrapper">
                    <div class="search-popup shadow rounded">
                        {!! $searchForm !!}
                        <button class="btn btn-primary" type="submit">Найти</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive text-nowrap">
            {!! $grid  !!}
        </div>
    </div>
@endsection
