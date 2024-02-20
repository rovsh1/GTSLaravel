<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('report-order')
    ->category(Factory::CATEGORY_REPORTS)
//    ->model(\App\Admin\Models\Reference\Airport::class)
    ->controller(\App\Admin\Http\Controllers\Report\OrderController::class, ['except' => ['show']])
    ->titles([
        "index" => "Отчет по заказам"
    ])
    ->readOnly();
