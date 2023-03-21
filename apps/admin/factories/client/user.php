<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('client-user')
    ->category(Factory::CATEGORY_CLIENT)
//    ->model(\App\Admin\Models\Reference\Airport::class)
//    ->controller(\App\Admin\Http\Controllers\Data\AirportController::class, ['except' => ['show']])
    ->titles([
        "index" => "Пользователи",
        "create" => "Новый пользователь"
    ])
    ->priority(90);