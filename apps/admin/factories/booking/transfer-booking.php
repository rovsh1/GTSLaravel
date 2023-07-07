<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('transfer-booking')
    ->category(Factory::CATEGORY_BOOKING)
    ->group('bookings')
//    ->model(\App\Admin\Models\System\Constant::class)
    ->controller(\App\Admin\Http\Controllers\Booking\Transfer\BookingController::class)
    ->titles([
        "index" => "Брони транспортных услуг",
        "create" => "Новая бронь"
    ])
    ->priority(207);
