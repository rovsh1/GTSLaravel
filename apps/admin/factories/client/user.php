<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('client-user')
    ->category(Factory::CATEGORY_CLIENT)
    ->model(\App\Admin\Models\Client\User::class)
    ->controller(\App\Admin\Http\Controllers\Client\UserController::class, ['except' => ['show']])
    ->titles([
        "index" => "Пользователи",
        "create" => "Новый пользователь"
    ])
    ->priority(90);
