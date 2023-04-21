<!DOCTYPE html>
<html>
    <head>
        {!! Layout::meta() !!}

        @yield('scripts')
        @yield('styles')
    </head>
    <body class="{{ Layout::bodyClass() }}">
        @yield('layout__content')
    </body>
</html>
