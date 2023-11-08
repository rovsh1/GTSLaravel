<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('invoice')
    ->category(Factory::CATEGORY_FINANCE)
    ->model(\App\Admin\Models\Invoice\Invoice::class)
    ->controller(\App\Admin\Http\Controllers\Invoice\InvoiceController::class)
    ->titles([
        "index" => "Инвойсы"
    ])
    ->priority(20);
