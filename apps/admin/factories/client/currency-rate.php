<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('client-currency-rate')
    ->category(Factory::CATEGORY_CLIENT)
    ->group(Factory::GROUP_SETTINGS)
    ->model(\App\Admin\Models\Client\CurrencyRate::class)
    ->controller(\App\Admin\Http\Controllers\Client\CurrencyRateController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Курсы валют',
        'create' => 'Новый курс валют'
    ]);
