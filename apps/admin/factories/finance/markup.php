<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('markup')
    ->category(Factory::CATEGORY_FINANCE)
//    ->model(\App\Admin\Models\Reference\Airport::class)
//    ->controller(\App\Admin\Http\Controllers\Data\AirportController::class, ['except' => ['show']])
    ->titles([
        "index" => "Наценки"
    ])
    ->priority(10);
