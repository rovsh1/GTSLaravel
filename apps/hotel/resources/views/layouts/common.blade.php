<!DOCTYPE html>
<html lang="ru">
    <head>
        {!! Meta::toHtml() !!}

        @vite('resources/js/support/jquery.ts')
        @yield('styles')
    </head>
    <body>
        @yield('layout__content')
        @yield('scripts')
    </body>
</html>
