@extends('layouts/dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ $title }}
            <a href="{{ route('reference.country.create') }}">
                <button type="button" class="btn btn-primary float-end">Добавить</button>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                {!! $grid  !!}
            </div>
        </div>
    </div>
@endsection
