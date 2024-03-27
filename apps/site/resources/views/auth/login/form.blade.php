@extends('layouts.common')

{{--@section('styles')--}}
{{--    @vite('resources/views/auth/login/login.scss')--}}
{{--@endsection--}}

{{--@section('scripts')--}}
{{--    @vite('resources/views/auth/login/login.ts')--}}
{{--@endsection--}}

@section('content')
    <div class="form-signin w-100 m-auto">

        <div class="content-body">
            <div class="card card-form">
                <div class="card-body">
                    <form method="POST">
                        <h1 class="h3 mb-3 fw-normal">{{ __('auth.login.form.header') }}</h1>

                        {!! $form !!}

                        <div class="mb-2">
                            <button class="btn btn-primary d-grid w-100" type="submit">
                                {{ __('auth.login.form.button') }}
                            </button>
                        </div>
                    </form>

                    <a href="{{ route('auth.register') }}">{{ __('auth.register.label') }}</a>
                    <a href="{{ route('auth.forgot-password') }}">{{ __('auth.forgot-password.label') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
