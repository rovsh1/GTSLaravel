@extends('layouts.blank')

@section('styles')
    @vite('resources/views/auth/login/login.scss')
@endsection

@section('scripts')
    @vite('resources/views/auth/login/login.ts')
@endsection

@section('content')
    <div class="form-signin w-100 m-auto">

        <div class="content-body">
            <div class="card card-form">
                <div class="card-body">
                    <form method="POST">
                        <h1 class="h3 mb-3 fw-normal">{{ __('auth.login.form.header') }}</h1>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="post">
                        <div class="row form-field field-text field-login field-required">
                            <label for="form_data_login" class="col-sm-4 col-form-label">Логин</label>
                            <div class="col-sm-8 d-flex align-items-center">
                                <input type="text" class="form-control" name="auth[login]" id="form_data_login" required=""
                                    autocomplete="username" value="">
                            </div>
                        </div>
                        <div class="row form-field field-password field-required">
                            <label for="form_data_password" class="col-sm-4 col-form-label">Пароль</label>
                            <div class="col-sm-8 d-flex align-items-center">
                                <input type="password" class="form-control" name="auth[password]" id="form_data_password" required=""
                                    autocomplete="current-password" value="">
                            </div>
                        </div>
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