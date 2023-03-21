@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body">
        <div class="card">

        </div>
        <div class="br-2"></div>
        @include('components/contacts-card', ['contacts' => $contacts])
    </div>
@endsection
