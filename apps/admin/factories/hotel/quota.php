<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-quota')
    ->category(Factory::CATEGORY_HOTEL)
    //->model(\App\Admin\Models\Hotel\Dis::class)
    //->controller(\App\Admin\Http\Controllers\Hotel\HotelController::class)
    ->titles([
        'index' => 'Доступность',
        'create' => 'Новая квота'
    ])
    ->priority(90);