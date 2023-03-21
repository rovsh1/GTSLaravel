<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('landmark')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
    ->model(\App\Admin\Models\Reference\Landmark::class)
    ->controller(\App\Admin\Http\Controllers\Data\LandmarkController::class, ['except' => ['show']])
    ->titles([
        "index" => "Достопримечательности",
        "create" => "Новая достопримечательность"
    ]);