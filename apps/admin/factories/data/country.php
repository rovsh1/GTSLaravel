<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('country')
    ->category(Factory::CATEGORY_DATA)
    ->group(Factory::GROUP_REFERENCE)
//    ->alias('reference.country')
    ->route('countries')
    ->model(\App\Admin\Models\Reference\Country::class)
    ->controller(\App\Admin\Http\Controllers\Data\CountryController::class, ['except' => ['show']])
    ->titles([
        "index" => "Страны",
        "create" => "Новая страна"
    ])
    ->priority(60);