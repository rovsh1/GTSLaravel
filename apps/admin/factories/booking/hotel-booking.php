<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-booking')
    ->category(Factory::CATEGORY_BOOKING)
    ->group('bookings')
    ->controller(\App\Admin\Http\Controllers\Booking\Hotel\BookingController::class)
    ->titles([
        "index" => "Брони отелей",
        "create" => "Новая бронь"
    ])
    ->views([
        'index' => 'hotel-booking.main.main',
        'show' => 'hotel-booking.show.show',
        'form' => 'hotel-booking.form.form'
    ])
    ->priority(209);
