<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('administrator-post')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_SETTINGS)
    ->model(\App\Admin\Models\Administrator\Post::class)
    ->controller(\App\Admin\Http\Controllers\Administration\PostController::class, ['except' => ['show']])
    ->titles([
        "index" => "Должности",
        "create" => "Новая должность"
    ]);