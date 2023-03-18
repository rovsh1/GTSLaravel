<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('additional-reservation')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group('reservations')
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Брони доп. услуг",
        "create" => "Новая бронь"
    ])
    ->priority(206);