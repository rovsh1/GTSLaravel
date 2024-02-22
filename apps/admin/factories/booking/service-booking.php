<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('service-booking')
    ->category(Factory::CATEGORY_BOOKING)
    ->group('bookings')
//    ->model(\App\Admin\Models\System\Constant::class)
    ->controller(\App\Admin\Http\Controllers\Booking\Service\BookingController::class)
    ->titles([
        "index" => "Брони услуг",
        "create" => "Новая бронь"
    ])
    ->views([
        'index' => 'booking.services.main.main',
        'show' => 'booking.services.show.show',
        'form' => 'booking.services.form.form'
    ])
    ->priority(207);
