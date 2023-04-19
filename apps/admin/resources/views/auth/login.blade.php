@extends('layouts/blank')

@section('head')
    @vite('resources/sass/pages/auth.scss')
@endsection

@section('content')
    <div class="form-signin w-100 m-auto">

        <div class="content-body">
            <div class="card card-form">
                <div class="card-body">
                    <form action="{{route('auth.login')}}" method="POST">
                        <h1 class="h3 mb-3 fw-normal">{{ __('auth.login.form.header') }}</h1>

                        {!! $form !!}
                        <div class="mb-2">
                            <button class="btn btn-primary d-grid w-100" type="submit">
                                {{ __('auth.login.form.button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
