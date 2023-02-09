<!DOCTYPE html>

{{--<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">--}}

{!! $layout->head  !!}

<body>
  @yield('layout__content')
  @include('layouts/sections/scripts')
</body>

</html>
