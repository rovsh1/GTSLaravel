<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('report-service-booking')
    ->category(Factory::CATEGORY_REPORTS)
//    ->model(\App\Admin\Models\Reference\Airport::class)
    ->controller(\App\Admin\Http\Controllers\Report\ServiceBookingController::class, ['except' => ['show']])
    ->titles([
        "index" => "Отчет по броням услуг"
    ])
    ->readOnly();
