@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body">
        @include('_partials/components/contacts-card')
    </div>
@endsection
