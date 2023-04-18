<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('additional-booking')
    ->category(Factory::CATEGORY_BOOKING)
    ->group('bookings')
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Брони доп. услуг",
        "create" => "Новая бронь"
    ])
    ->priority(206);