<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('locale-dictionary')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_SETTINGS)
//    ->model(\App\Admin\Models\Reference\PaymentMethod::class)
    ->controller(
        \App\Admin\Http\Controllers\Data\LocaleDictionaryController::class,
        ['except' => ['show', 'create', 'store', 'edit', 'destroy']]
    )
    ->titles([
        "index" => "Справочник переводов",
    ]);