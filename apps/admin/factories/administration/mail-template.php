<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('mail-template')
    ->category(Factory::CATEGORY_ADMINISTRATION)
    ->group(Factory::GROUP_SETTINGS)
    ->route('mail-templates')
    ->model(\App\Admin\Models\System\Constant::class)
    ->controller(\App\Admin\Http\Controllers\Administration\ConstantController::class, ['except' => ['show']])
    ->titles([
        "index" => "Шаблоны писем",
        "create" => "Новый шаблон"
    ]);