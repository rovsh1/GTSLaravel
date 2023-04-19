<!DOCTYPE html>
<head>
    {!! Layout::meta() !!}
    @vite('resources/sass/pages/main.scss')
    @vite('resources/js/pages/main.ts')
    @yield('head')
</head>
<body class="{{ Layout::bodyClass() }}">
@yield('layout__content')
</body>
</html>
