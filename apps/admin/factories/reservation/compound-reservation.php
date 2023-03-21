<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('compound-reservation')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group('reservations')
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Составные брони",
        "create" => "Новая бронь"
    ])
    ->priority(210);