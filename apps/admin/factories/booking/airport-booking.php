<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('airport-booking')
    ->category(Factory::CATEGORY_BOOKING)
    ->group('bookings')
    //   ->model(\App\Admin\Models\System\Constant::class)
    ->controller(\App\Admin\Http\Controllers\Booking\Airport\BookingController::class)
    ->titles([
        "index" => "Брони услуг аэропорта",
        "create" => "Новая бронь"
    ])
    ->views([
        'form' => 'airport-booking.form.form'
    ])
    ->priority(208);
