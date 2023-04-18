<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('booking-order')
    ->category(Factory::CATEGORY_BOOKING)
    ->group('bookings')
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Заказы",
        "create" => "Новый заказ"
    ])
    ->priority(210);