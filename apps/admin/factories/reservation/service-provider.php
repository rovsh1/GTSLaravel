<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('service-provider')
    ->category(Factory::CATEGORY_RESERVATION)
    ->group(Factory::GROUP_SETTINGS)
    ->model(\App\Admin\Models\ServiceProvider\Provider::class)
    ->controller(\App\Admin\Http\Controllers\ServiceProvider\ProviderController::class)
    ->titles([
        "index" => "Поставщики услуг",
        "create" => "Новый поставщик"
    ])
    ->priority(50);