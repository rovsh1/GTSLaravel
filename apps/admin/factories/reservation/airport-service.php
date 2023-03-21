<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('airport-service')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group(Factory::GROUP_ADDITIONAL_SERVICES)
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Услуги в аэропорту",
        "create" => "Новая услуга"
    ]);