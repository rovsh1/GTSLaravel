<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('supplier-type')
    ->category(Factory::CATEGORY_SUPPLIER)
    ->model(\App\Admin\Models\Reference\SupplierType::class)
    ->controller(\App\Admin\Http\Controllers\Supplier\SupplierTypeController::class)
    ->titles([
        "index" => "Типы поставщиков",
        "create" => "Новый тип"
    ])
    ->priority(40);
