@extends('layouts/common' )

@section('layout__content')
    <div class="dashboard-wrapper">
        @include('layouts/main/header')

        {!! Layout::sitemap() !!}

        <main class="main-wrapper">
            {!! Layout::sidebar() !!}

            <div class="content-wrapper">
                <section class="content">@yield('content')</section>
            </div>
        </main>
    </div>
@endsection
