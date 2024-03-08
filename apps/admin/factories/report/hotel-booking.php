<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('report-hotel-booking')
    ->category(Factory::CATEGORY_REPORTS)
//    ->model(\App\Admin\Models\Reference\Airport::class)
    ->controller(\App\Admin\Http\Controllers\Report\HotelBookingController::class, ['except' => ['show']])
    ->titles([
        "index" => "Отчет по броням отелей"
    ])
    ->readOnly();
