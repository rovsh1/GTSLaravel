@extends('layouts/main')

@section('content')
    <div class="card grid-card">
        <div class="card-header mb-2">
            <div class="d-flex align-items-center">
                <h5 class="mb-0">{{ $title }}</h5>
                <div class="flex-grow-1"></div>
                <form method="get" class="grid-filters">
                    <button type="button" class="btn btn-light"><i class="icon">filter_alt</i></button>
                    <div class="quicksearch-wrapper">
                        {!! $quicksearch !!}
                        <button class="icon" type="submit" title="Найти">search</button>
                    </div>
                    <div class="search-popup-wrapper">
                        <div class="search-popup shadow rounded">
                            {!! $searchForm !!}
                            <button class="btn btn-primary" type="submit">Найти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            {!! $grid  !!}
        </div>
    </div>
@endsection
