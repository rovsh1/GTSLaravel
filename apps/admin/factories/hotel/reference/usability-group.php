<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-usability-group')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Hotel\Reference\UsabilityGroup::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\UsabilityGroupController::class, ['except' => ['show']])
    ->titles([
        "index" => "Категории удобств",
        "create" => "Новая категория"
    ]);