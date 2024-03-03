<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('report-profit')
    ->category(Factory::CATEGORY_REPORTS)
//    ->model(\App\Admin\Models\Reference\Airport::class)
    ->controller(\App\Admin\Http\Controllers\Report\ProfitController::class, ['except' => ['show']])
    ->titles([
        "index" => "Отчет по прибыли"
    ])
    ->readOnly();
