<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('cancel-reason')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Reference\CancelReason::class)
    ->controller(\App\Admin\Http\Controllers\Data\CancelReasonController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Причины отказа',
        'create' => 'Новая причина отказа'
    ]);
