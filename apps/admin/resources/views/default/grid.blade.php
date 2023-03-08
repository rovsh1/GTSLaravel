@extends('layouts/main')

@section('content')
    <div class="card grid-card">
        <div class="card-header">
            {{ $title }}
            <form method="get" class="grid-filters">
                <div class="search-wrapper">{!! $searchForm !!}</div>
                <div class="quicksearch-wrapper">{!! $quicksearch !!}</div>
            </form>
        </div>
        <div class="table-responsive text-nowrap">
            {!! $grid  !!}
        </div>
    </div>
@endsection
