@extends('layouts/dashboard')

@section('content')
    <div class="card">
        <h5 class="card-header">
            {{ $title }}
            <a href="{{ route('reference.country.index') }}">
                <button type="button" class="btn btn-primary float-end">Назад</button>
            </a>
        </h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    {!! $form !!}
                </div>
            </div>
        </div>
    </div>
@endsection
