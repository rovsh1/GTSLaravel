@extends('layouts.common')

{{--@section('styles')--}}
{{--    @vite('resources/views/auth/register/register.scss')--}}
{{--@endsection--}}

{{--@section('scripts')--}}
{{--    @vite('resources/views/auth/register/register.ts')--}}
{{--@endsection--}}

@section('content')
    <div class="form-signin w-100 m-auto">

        <div class="content-body">
            <div class="card card-form">
                <div class="card-body">
                    <form method="POST">
                        <h1 class="h3 mb-3 fw-normal">{{ __('auth.register.form.header') }}</h1>

                        {!! $form !!}

                        <div class="mb-2">
                            <button class="btn btn-primary d-grid w-100" type="submit">
                                {{ __('auth.register.form.button') }}
                            </button>
                        </div>
                    </form>

                    <a href="{{ route('auth.login') }}">{{ __('auth.login.form.button') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
