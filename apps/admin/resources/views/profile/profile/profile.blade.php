@extends('layouts.main')

@section('styles')
    @vite('resources/views/profile/profile/profile.scss')
@endsection

@section('scripts')
    @vite('resources/views/profile/profile/profile.ts')
@endsection

@section('content')
    <section class="profile-content" id="profile-settings">
        <h1>{{ $title }}</h1>

        <div class="block">
            <div class="block-title">Основная информация</div>
            <div class="block-body">
                <div class="block-row" data-action="photo">
                    <div class="label">Фотография</div>
                    <div class="value">
                        <x-file-image :file="$avatar"/>
                    </div>
                </div>
                <div class="block-row" data-action="settings">
                    <div class="label">Имя в системе (ФИО)</div>
                    <div class="value">{{ $user->presentation }}</div>
                </div>
                <div class="block-row" data-action="settings">
                    <div class="label">Электронная почта</div>
                    <div class="value">{{ $user->email }}</div>
                </div>
                <div class="block-row" data-action="settings">
                    <div class="label">Телефон</div>
                    <div class="value">{{ $user->phone }}</div>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">Пароль</div>
            <div class="block-body">
                <div class="block-row" data-href="{{ route('profile.password') }}">
                    <div class="value">Сменить пароль</div>
                </div>
            </div>
        </div>
    </section>
@endsection
