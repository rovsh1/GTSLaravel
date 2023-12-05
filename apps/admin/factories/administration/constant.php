<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('constant')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_SETTINGS)
    ->route('constants')
    ->priority(10)
    ->model(\Module\Shared\Infrastructure\Models\Constant::class)
    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Константы"
    ])
    ->permissions(['read', 'update']);