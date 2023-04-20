<!DOCTYPE html>
<html>
    <head>
        {!! Layout::meta() !!}

        @vite('resources/assets/jquery.ts')

        @hasSection('scripts')
            @yield('scripts')
        @else
            @vite('resources/views/main.ts')
        @endif

        @hasSection('styles')
            @yield('styles')
        @else
            @vite('resources/views/main.scss')
        @endif
    </head>
    <body class="{{ Layout::bodyClass() }}">
        @yield('layout__content')
    </body>
</html>
