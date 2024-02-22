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
        'index' => 'booking.hotel.main.main',
        'show' => 'booking.hotel.show.show',
        'form' => 'booking.hotel.form.form'
    ])
    ->priority(209);
