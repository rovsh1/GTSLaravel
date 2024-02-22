<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('markup-group')
    ->category(Factory::CATEGORY_CLIENT)
    ->group(Factory::GROUP_SETTINGS)
    ->alias('group')
    ->model(\App\Admin\Models\Pricing\MarkupGroup::class)
    ->controller(\App\Admin\Http\Controllers\Pricing\MarkupGroupController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Наценки',
        'create' => 'Новая запись'
    ])
    ->priority(10);
