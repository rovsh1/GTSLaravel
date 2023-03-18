<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-discount')
    ->category(Factory::CATEGORY_HOTEL)
    //->model(\App\Admin\Models\Hotel\Dis::class)
    //->controller(\App\Admin\Http\Controllers\Hotel\HotelController::class)
    ->titles([
        'index' => 'Скидки',
        'create' => 'Новая скидка'
    ]);