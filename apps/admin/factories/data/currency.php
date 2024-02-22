<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('currency')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
    ->route('currencies')
    ->model(\App\Admin\Models\Reference\Currency::class)
    ->controller(\App\Admin\Http\Controllers\Data\CurrencyController::class, ['except' => ['show']])
    ->readOnly()
    ->titles([
        "index" => "Валюты",
        "create" => "Новая валюта"
    ]);