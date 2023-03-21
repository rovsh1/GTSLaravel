<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-bed-type')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Hotel\Reference\BedType::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\BedTypeController::class, ['except' => ['show']])
    ->titles([
        "index" => "Типы кровати",
        "create" => "Новый тип"
    ]);