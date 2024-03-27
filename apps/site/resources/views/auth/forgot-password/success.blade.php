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
                    <p>{{ __('auth.forgot-password.success.description') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
