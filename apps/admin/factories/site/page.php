<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('site-page')
    ->category(Factory::CATEGORY_SITE)
    ->group(Factory::GROUP_CONTENT)
//    ->model(\App\Admin\Models\Reference\Airport::class)
//    ->controller(\App\Admin\Http\Controllers\Data\AirportController::class, ['except' => ['show']])
    ->titles([
        "index" => "Страницы сайта",
        "create" => "Новая страница"
    ])
    ->priority(150);