<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('transport-car')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
    ->repository(\App\Admin\Repositories\TransportCarRepository::class)
    ->controller(\App\Admin\Http\Controllers\Data\TransportCarController::class, ['except' => ['show']])
    ->titles([
        "index" => "Транспорт",
        "create" => "Новый транспорт"
    ]);