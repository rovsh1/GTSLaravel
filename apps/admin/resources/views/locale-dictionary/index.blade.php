@extends('layouts.main')

@section('styles')
    @vite('resources/views/default/grid/grid.scss')
@endsection

@section('styles')
    @vite('resources/views/locale-dictionary/locale-dictionary.scss')
@endsection

@section('scripts')
    @vite('resources/views/locale-dictionary/locale-dictionary.ts')
@endsection

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>
    <div class="content-body" id="locale-dictionary"></div>
@endsection
