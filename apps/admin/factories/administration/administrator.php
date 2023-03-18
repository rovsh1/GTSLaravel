<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('administrator')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_SYSTEM)
    ->model(\App\Admin\Models\Administrator\Administrator::class)
    ->controller(\App\Admin\Http\Controllers\Administration\AdministratorController::class, ['except' => ['show']])
    ->titles([
        "index" => "Администраторы",
        "create" => "Новый администратор"
    ])
    ->priority(200);