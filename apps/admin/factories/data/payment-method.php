<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('payment-method')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Reference\PaymentMethod::class)
    ->controller(\App\Admin\Http\Controllers\Data\PaymentMethodController::class, ['except' => ['show']])
    ->titles([
        "index" => "Методы оплат",
        "create" => "Новый метод"
    ]);