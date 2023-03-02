@extends('layouts/default')

@section('content')
    <div class="card">
        <h5 class="card-header">
            {{ $title }}
            <a href="{{ route('country.create') }}">
                <button type="button" class="btn btn-primary float-end">Добавить</button>
            </a>
        </h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                {!! $grid  !!}
            </div>
        </div>
    </div>
@endsection
