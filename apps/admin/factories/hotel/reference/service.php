<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-service')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_REFERENCE)
    ->model(\App\Admin\Models\Hotel\Reference\Service::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\ServiceController::class, ['except' => ['show']])
    ->titles([
        "index" => "Услуги в отеле",
        "create" => "Новая услуга"
    ]);