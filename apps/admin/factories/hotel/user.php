<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-user')
    ->category(Factory::CATEGORY_HOTEL)
    ->model(\App\Admin\Models\Hotel\User::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\UserController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Пользователи',
        'create' => 'Новый пользователь'
    ]);