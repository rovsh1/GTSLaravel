@extends('layouts.main')

@section('styles')
    @vite('resources/views/default/grid/grid.scss')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>

        <div class="flex-grow-1"></div>
        <form method="get" class="grid-filters">
            @if(!empty($quicksearch))
                <div class="quicksearch-wrapper">
                    {!! $quicksearch !!}
                    <button class="icon" type="submit" title="Найти">search</button>
                </div>
            @endif
        </form>
    </div>

    @yield('content-body')
@endsection
