<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('client')
    ->category(Factory::CATEGORY_CLIENT)
//    ->model(\App\Admin\Models\Reference\Airport::class)
//    ->controller(\App\Admin\Http\Controllers\Data\AirportController::class, ['except' => ['show']])
    ->titles([
        "index" => "Клиенты",
        "create" => "Новый клиент"
    ])
    ->priority(100);