@extends('layouts/common' )

@section('layout__content')
    <div class="dashboard-wrapper">
        @include('layouts/main/header')

        {!! Layout::sidebar() !!}

        <main class="main-wrapper">
            <div class="content-wrapper">
                <section class="content">@yield('content')</section>
            </div>
        </main>
    </div>
@endsection
