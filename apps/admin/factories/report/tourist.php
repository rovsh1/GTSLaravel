<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('report-tourist')
    ->category(Factory::CATEGORY_REPORTS)
//    ->model(\App\Admin\Models\Reference\Airport::class)
//    ->controller(\App\Admin\Http\Controllers\Data\AirportController::class, ['except' => ['show']])
    ->titles([
        "index" => "Отчет по туристам"
    ])
    ->readOnly();