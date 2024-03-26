<!DOCTYPE html>
<html lang="ru">
    <head>
        {{--        @vite('resources/js/support/jquery.ts')--}}
        @yield('styles')

        <title>GoToStans - Title</title>
    </head>
    <body class="">
        @include('layouts.main.header')
        @yield('content')
        @yield('scripts')
        @include('layouts.main.footer')
    </body>
</html>
