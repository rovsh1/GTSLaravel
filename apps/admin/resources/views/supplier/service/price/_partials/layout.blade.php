@extends('layouts.main')

@section('styles')
    @vite('resources/views/default/grid/grid.scss')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <ul class="nav nav-pills" style="margin-left: 1rem;">
            <li class="nav-item">
                <a
                  class="nav-link {{ request()->routeIs('supplier.service.transfer.prices.index') ? 'active' : '' }}"
                  href="{{ route('supplier.service.transfer.prices.index', $provider) }}"
                >
                    Транспорт
                </a>
            </li>
            <li class="nav-item">
                <a
                  class="nav-link {{ request()->routeIs('supplier.service.airport.prices.index') ? 'active' : '' }}"
                  href="{{ route('supplier.service.airport.prices.index', $provider) }}"
                >
                    Аэропорт
                </a>
            </li>
            <li class="nav-item">
                <a
                        class="nav-link {{ request()->routeIs('supplier.service.other.prices.index') ? 'active' : '' }}"
                        href="{{ route('supplier.service.other.prices.index', $provider) }}"
                >
                    Прочие
                </a>
            </li>
        </ul>

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
