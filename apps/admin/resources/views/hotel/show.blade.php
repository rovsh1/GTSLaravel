@extends('layouts/main')

@section('content')
    <div class="content-header">
        <div class="title">{{ $title }}</div>

        {!! Layout::actions() !!}
    </div>

    <div class="content-body">
        <div class="card card-form">
            <div class="card-body">
                {!! $params !!}
            </div>
        </div>
    </div>
@endsection
