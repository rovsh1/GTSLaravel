<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-type')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Hotel\Reference\Type::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\TypeController::class, ['except' => ['show']])
    ->titles([
        "index" => "Типы отелей",
        "create" => "Новый тип"
    ]);