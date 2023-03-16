@extends('layouts/common' )

@section('layout__content')
    <div class="dashboard-wrapper">
        @include('layouts/main/header')

        {!! Layout::sitemap() !!}

        {!! Layout::sidebar() !!}

        <main class="main-wrapper">
            <div class="content-wrapper">
                <section class="content">
                    {!! Layout::breadcrumbs() !!}

                    @yield('content')
                </section>
            </div>
        </main>

        @include('layouts/main/footer')
    </div>
@endsection
