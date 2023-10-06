<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('railway-station')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
    ->route('railway-stations')
    ->model(\App\Admin\Models\Reference\RailwayStation::class)
    ->controller(\App\Admin\Http\Controllers\Data\RailwayStationController::class, ['except' => ['show']])
    ->titles([
        "index" => "Ж/д вокзалы",
        "create" => "Новый вокзал"
    ])
    ->priority(40);