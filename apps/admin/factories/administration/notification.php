<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('notification')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_NOTIFICATION)
    ->route('notifications')
//    ->model(\App\Admin\Models\System\Constant::class)
//    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Уведомления"
    ]);