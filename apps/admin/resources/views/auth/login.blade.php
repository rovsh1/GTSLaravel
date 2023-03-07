@extends('layouts/blank')

@section('content')
    <div class="container-xxl">
        <form class="mb-3" action="{{route('auth.login')}}" method="POST">
            {!! $form !!}
            <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Войти</button>
            </div>
        </form>
    </div>
@endsection
