@extends('layouts.common')

@section('styles')
    @vite('resources/views/file-manager/file-manager.scss')
@endsection

@section('scripts')
    @vite('resources/assets/jquery.ts')
    @vite('resources/views/file-manager/file-manager.ts')
@endsection

@section('layout__content')
@endsection
