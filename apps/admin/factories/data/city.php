<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('city')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
    ->route('cities')
    ->model(\App\Admin\Models\Reference\City::class)
    ->controller(\App\Admin\Http\Controllers\Data\CityController::class, ['except' => ['show']])
    ->titles([
        "index" => "Города",
        "create" => "Новый город"
    ])
    ->priority(50);