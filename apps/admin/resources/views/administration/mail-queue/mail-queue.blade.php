@extends('default.grid.grid')

@section('styles')
    @vite('resources/views/default/grid/grid.scss')
@endsection

@section('scripts')
    @vite('resources/views/administration/mail-queue/mail-queue.ts')
@endsection
