<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-service-type')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Hotel\Reference\ServiceType::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\ServiceTypeController::class, ['except' => ['show']])
    ->titles([
        "index" => "Категории услуг",
        "create" => "Новая категория"
    ]);