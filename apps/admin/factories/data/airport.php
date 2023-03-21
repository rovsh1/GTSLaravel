<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('airport')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
    ->route('airports')
    ->model(\App\Admin\Models\Reference\Airport::class)
    ->controller(\App\Admin\Http\Controllers\Data\AirportController::class, ['except' => ['show']])
    ->titles([
        "index" => "Аэропорты",
        "create" => "Новый аэропорт"
    ])
    ->priority(40);