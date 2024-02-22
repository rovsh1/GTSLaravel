@extends('default.grid.grid')

@section('styles')
    @vite('resources/views/default/grid/grid.scss')
@endsection

@section('scripts')
    {!! Js::variables([
        'createUserUrl' => $createUserUrl,
        'searchUserUrl' => $searchUserUrl,
    ]) !!}

    @vite('resources/views/client/user/main/main.ts')
@endsection
