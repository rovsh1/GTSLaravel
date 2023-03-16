@extends('layouts/main')

@section('content')
    <section class="profile-content" id="profile-settings">
        <h1>{{ $title }}</h1>

        <div class="block">
            <div class="block-title">Основная информация</div>
            <div class="block-body">
                <div class="block-row" data-action="photo">
                    <div class="label">Фотография</div>
                    <div class="value"><?php
                                       //user_avatar($user)?></div>
                </div>
                <div class="block-row" data-action="name">
                    <div class="label">Имя</div>
                    <div class="value">{{ $user->presentation }}</div>
                </div>
                <div class="block-row" data-action="birthday">
                    <div class="label">Дата рождения</div>
                    <div class="value"><?php
                                       //$user->birthday ? $user->birthday->format('date') : $valueEmpty ?></div>
                </div>
                <div class="block-row" data-action="gender">
                    <div class="label">Пол</div>
                    <div class="value"><?php
                                       //$user->gender ? Ustabor\Infrastructure\Enums\User\UserGender::getLabel($user->gender) : $valueEmpty?></div>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">Контактная информация</div>
            <div class="block-body">
                <div class="block-row">
                    <div class="label">Электронная почта</div>
                    <div class="value">

                    </div>
                </div>
                <div class="block-row">
                    <div class="label">Телефон</div>
                    <div class="value">

                    </div>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">Пароль</div>
            <div class="block-body">
                <div class="block-row" data-action="password">
                    <!--<div class="label">Сменить пароль</div>-->
                    <div class="value">Сменить пароль</div>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">Удаление профиля</div>
            <div class="block-body">
                <div class="block-row" data-action="delete">
                    <div class="value">Удалить профиль</div>
                </div>
            </div>
        </div>
    </section>
@endsection
