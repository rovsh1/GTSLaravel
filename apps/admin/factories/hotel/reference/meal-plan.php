<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('meal-plan')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Hotel\MealPlan::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\MealPlanController::class, ['except' => ['show']])
    ->titles([
        "index" => "Типы питания",
        "create" => "Новый тип"
    ]);
