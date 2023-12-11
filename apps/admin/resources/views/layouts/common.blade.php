<!DOCTYPE html>
<html lang="ru">
    <head>
        {!! Layout::meta() !!}

        @vite('resources/assets/jquery.ts')
        @yield('styles')
    </head>
    <body class="{{ Layout::bodyClass() }}">
        @yield('layout__content')
        @yield('scripts')
    </body>
</html>
