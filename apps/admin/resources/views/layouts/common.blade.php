<!DOCTYPE html>
<html lang="ru">
    <head>
        {!! Layout::meta() !!}

        @vite('resources/assets/jquery.ts')
        @yield('scripts')
        @yield('styles')
    </head>
    <body class="{{ Layout::bodyClass() }}">
        @yield('layout__content')
    </body>
</html>
