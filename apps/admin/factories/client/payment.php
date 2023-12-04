<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('payment')
    ->category(Factory::CATEGORY_CLIENT)
    ->model(\App\Admin\Models\Finance\Payment::class)
    ->controller(\App\Admin\Http\Controllers\Finance\PaymentController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Оплаты',
        'create' => 'Новый платеж'
    ])
    ->views([
        'index' => 'payment.main.main'
    ])
    ->priority(15);
