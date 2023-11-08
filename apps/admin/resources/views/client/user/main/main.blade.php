@extends('default.grid.grid')

@section('styles')
    @vite('resources/views/default/grid/grid.scss')
@endsection

@section('scripts')
    @vite('resources/views/client/user/main/main.ts')
@endsection

@section('head-end')
    <script>
      window['view-initial-data-client-user'] = {{ Js::from([
            'createUserUrl' => $createUserUrl,
            'searchUserUrl' => $searchUserUrl,
        ]) }}
    </script>
@endsection
