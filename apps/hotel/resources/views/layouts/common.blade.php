<!DOCTYPE html>
<html lang="ru">
    <head>
        {!! Meta::toHtml() !!}

        @vite('resources/assets/jquery.ts')
        @yield('styles')
        @yield('head-end')
    </head>
    <body>
        @yield('layout__content')
        @yield('scripts')
    </body>
</html>
