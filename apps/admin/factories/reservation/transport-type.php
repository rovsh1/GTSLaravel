<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('transport-type')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group(Factory::GROUP_REFERENCE)
    ->model(\App\Admin\Models\Reservation\TransportType::class)
    ->controller(\App\Admin\Http\Controllers\Reservation\TransportTypeController::class, ['except' => ['show']])
    ->titles([
        "index" => "Типы транспорта",
        "create" => "Новый тип"
    ])
    ->priority(20);