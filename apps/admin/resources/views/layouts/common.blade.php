<!DOCTYPE html>
<html lang="ru">
    <head>
        {!! Layout::meta() !!}

        @vite('resources/assets/jquery.ts')
        @yield('styles')
        @yield('head-end')
    </head>
    <body class="{{ Layout::bodyClass() }}">
        @yield('layout__content')
        @yield('scripts')
    </body>
</html>
