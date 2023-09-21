<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('locale-dictionary')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
    ->model(\App\Admin\Models\Reference\PaymentMethod::class)
    ->controller(\App\Admin\Http\Controllers\Data\PaymentMethodController::class, ['except' => ['show', 'create']])
    ->titles([
        "index" => "Справочник переводов",
    ]);