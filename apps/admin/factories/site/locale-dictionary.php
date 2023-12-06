<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('locale-dictionary')
    ->category(Factory::CATEGORY_SITE)
    ->group(Factory::GROUP_REFERENCE)
//    ->model(\App\Admin\Models\Reference\PaymentMethod::class)
    ->controller(
        \App\Admin\Http\Controllers\Data\LocaleDictionaryController::class,
        ['except' => ['show', 'create', 'store', 'edit', 'destroy']]
    )
    ->titles([
        "index" => "Справочник переводов",
    ]);