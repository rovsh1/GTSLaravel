@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                {{ $message }}
            </div>
        </div>
    </div>
@endsection
