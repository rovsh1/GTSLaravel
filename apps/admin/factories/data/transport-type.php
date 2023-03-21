<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('transport-type')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Reference\TransportType::class)
    ->controller(\App\Admin\Http\Controllers\Data\TransportTypeController::class, ['except' => ['show']])
    ->titles([
        "index" => "Типы транспорта",
        "create" => "Новый тип"
    ])
    ->priority(20);