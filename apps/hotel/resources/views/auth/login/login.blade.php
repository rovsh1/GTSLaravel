@extends('layouts.blank')

@section('styles')
    @vite('resources/views/auth/login/login.scss')
@endsection

@section('content')
<div class="content-auth w-100">
    <div class="content-auth-background">
        <div class="position-absolute content-auth-background-left-arrow"><img src="/images/login/left-arrow.svg"></img></div>
        <div class="position-absolute content-auth-background-logo-first"><img src="/images/login/logo.svg"></img></div>
        <div class="position-absolute content-auth-background-right-arrow"><img src="/images/login/right-arrow.svg"></img></div>
        <div class="position-absolute content-auth-background-logo-second"><img src="/images/login/logo2.svg"></img></div> 
    </div>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
