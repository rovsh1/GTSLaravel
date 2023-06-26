<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('client')
    ->category(Factory::CATEGORY_CLIENT)
    ->model(\App\Admin\Models\Client\Client::class)
    ->controller(\App\Admin\Http\Controllers\Client\ClientController::class)
    ->titles([
        "index" => "Клиенты",
        "create" => "Новый клиент"
    ])
    ->priority(100);
