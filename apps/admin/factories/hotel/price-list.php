<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-price-list')
    ->category(Factory::CATEGORY_HOTEL)
    ->model(\App\Admin\Models\Hotel\PriceList::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\PriceListController::class)
    ->titles([
        'index' => 'Прайс листы',
        'create' => 'Новый прайс лист'
    ]);