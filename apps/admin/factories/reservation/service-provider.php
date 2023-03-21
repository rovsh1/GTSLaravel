<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('service-provider')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group(Factory::GROUP_SETTINGS)
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Поставщики услуг",
        "create" => "Новый поставщик"
    ])
    ->priority(50);