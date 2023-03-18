<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('transfer-service')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group(Factory::GROUP_ADDITIONAL_SERVICES)
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Транспортные услуги",
        "create" => "Новая услуга"
    ]);