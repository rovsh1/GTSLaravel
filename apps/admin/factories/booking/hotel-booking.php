<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-booking')
    ->category(Factory::CATEGORY_BOOKING)
    ->group('bookings')
    ->repository(\App\Admin\Repositories\HotelBookingRepository::class)
    ->controller(\App\Admin\Http\Controllers\Booking\HotelBookingController::class)
    ->titles([
        "index" => "Брони отелей",
        "create" => "Новая бронь"
    ])
    ->views([
        'show' => 'hotel-booking.show.show',
        'form' => 'hotel-booking.form.form'
    ])
    ->priority(209);
