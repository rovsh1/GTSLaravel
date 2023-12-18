@extends('layouts.common')

@hasSection('scripts')
@else
    @section('scripts')
        @vite('resources/views/main.ts')
    @endsection
@endif

@hasSection('styles')
@else
    @section('styles')
        @vite('resources/views/main.scss')
    @endsection
@endif

@section('layout__content')
    <div class="dashboard-wrapper">
        @include('layouts/main/header')
        <div class="gts-content-wrapper">
            <div class="gts-menu-wrapper">
                {!! Layout::sitemap() !!}
            </div>
            <main class="main-wrapper">
                <div class="content-wrapper">
                    <section class="content">
                        {!! Layout::breadcrumbs() !!}

                        @yield('content')
                    </section>
                </div>
            </main>
        </div>
        @include('layouts/main/footer')
    </div>
@endsection
