<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('supplier')
    ->category(Factory::CATEGORY_CLIENT)
    ->model(\App\Admin\Models\Supplier\Provider::class)
    ->controller(\App\Admin\Http\Controllers\Supplier\ProviderController::class)
    ->titles([
        "index" => "Поставщики услуг",
        "create" => "Новый поставщик"
    ])
    ->priority(50);
