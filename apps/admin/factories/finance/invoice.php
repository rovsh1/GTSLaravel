<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('invoice')
    ->category(Factory::CATEGORY_FINANCE)
    ->model(\App\Admin\Models\Finance\Invoice::class)
    ->controller(\App\Admin\Http\Controllers\Finance\InvoiceController::class, ['except' => ['show']])
    ->titles([
        'index' => 'Инвойсы',
        'create' => 'Новый инвойс'
    ])
    ->views([
        'form' => 'invoice.form.form'
    ])
    ->priority(20);
