@extends('layouts.main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
        <ul class="nav nav-pills" style="margin-left: 1rem;">
            <li class="nav-item">
                <a
                  class="nav-link {{ request()->routeIs('service-provider.service.transfer.prices.index') ? 'active' : '' }}"
                  href="{{ route('service-provider.service.transfer.prices.index', $provider) }}"
                >
                    Транспорт
                </a>
            </li>
            <li class="nav-item">
                <a
                  class="nav-link {{ request()->routeIs('service-provider.service.airport.prices.index') ? 'active' : '' }}"
                  href="{{ route('service-provider.service.airport.prices.index', $provider) }}"
                >
                    Аэропорт
                </a>
            </li>
        </ul>
    </div>

    @yield('content-body')
@endsection
