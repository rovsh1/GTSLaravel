@extends('layouts/dashboard')

@section('content')
    <div class="card">
        <div class="card-header">
            {{ $title }}

            {!! Layout::actions() !!}
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                {!! $grid  !!}
            </div>
        </div>
    </div>
@endsection
