<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('report-booking')
    ->category(Factory::CATEGORY_REPORTS)
//    ->model(\App\Admin\Models\Reference\Airport::class)
    ->controller(\App\Admin\Http\Controllers\Report\BookingController::class, ['except' => ['show']])
    ->titles([
        "index" => "Отчет по броням"
    ])
    ->readOnly();
