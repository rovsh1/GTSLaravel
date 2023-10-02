<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('supplier')
    ->category(Factory::CATEGORY_CLIENT)
    ->model(\App\Admin\Models\Supplier\Supplier::class)
    ->controller(\App\Admin\Http\Controllers\Supplier\SupplierController::class)
    ->titles([
        "index" => "Поставщики услуг",
        "create" => "Новый поставщик"
    ])
    ->priority(50);
