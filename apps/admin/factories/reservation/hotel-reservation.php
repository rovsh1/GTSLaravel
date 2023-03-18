<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-reservation')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group('reservations')
    ->repository(\App\Admin\Repositories\HotelReservationRepository::class)
    ->controller(\App\Admin\Http\Controllers\Reservation\HotelReservationController::class)
    ->titles([
        "index" => "Брони отелей",
        "create" => "Новая бронь"
    ])
    ->priority(209);