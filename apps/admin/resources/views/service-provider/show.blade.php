@extends('layouts/main')

@section('content')
    {!! ContentTitle::default() !!}

    <div class="content-body">
        @include('_partials/components/contacts-card')
    </div>
@endsection
