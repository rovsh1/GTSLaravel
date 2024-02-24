<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('payment')
    ->category(Factory::CATEGORY_CLIENT)
    ->model(\App\Admin\Models\Supplier\Payment::class)
    ->controller(\App\Admin\Http\Controllers\Client\PaymentController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Оплаты',
        'create' => 'Новый платеж'
    ])
    ->views([
        'index' => 'client-payment.main.main',
        'form' => 'client-payment.form.form',
    ])
    ->priority(15);
