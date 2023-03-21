<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('constant')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_SETTINGS)
    ->route('constants')
    ->model(\App\Admin\Models\System\Constant::class)
    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Константы",
        "create" => "Новая константа"
    ])
    ->permissions(['read', 'update']);