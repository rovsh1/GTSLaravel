<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-usability')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_REFERENCE)
    ->model(\App\Admin\Models\Hotel\Reference\Usability::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\UsabilityController::class, ['except' => ['show']])
    ->titles([
        "index" => "Удобства",
        "create" => "Новая запись"
    ]);