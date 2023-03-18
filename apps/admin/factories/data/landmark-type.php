<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('landmark-type')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Reference\LandmarkType::class)
    ->controller(\App\Admin\Http\Controllers\Data\LandmarkTypeController::class, ['except' => ['show']])
    ->titles([
        "index" => "Типы достопримечательностей",
        "create" => "Новый тип"
    ]);