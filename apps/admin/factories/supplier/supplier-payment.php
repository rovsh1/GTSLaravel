<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('supplier-payment')
    ->category(Factory::CATEGORY_SUPPLIER)
    ->model(\App\Admin\Models\Supplier\Payment::class)
    ->controller(\App\Admin\Http\Controllers\Supplier\PaymentController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Оплаты поставщику',
        'create' => 'Новый платеж'
    ])
    ->views([
        'index' => 'supplier-payment.main.main',
        'form' => 'supplier-payment.form.form',
    ])
    ->priority(15);
