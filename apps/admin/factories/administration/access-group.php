<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('access-group')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_SYSTEM)
    ->repository(\App\Admin\Repositories\AccessGroupRepository::class)
    ->controller(\App\Admin\Http\Controllers\Administration\AccessGroupController::class, ['except' => ['show']])
    ->titles([
        "index" => "Группы доступа",
        "create" => "Новая группа"
    ])
    ->priority(190);