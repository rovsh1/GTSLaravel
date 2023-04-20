<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel')
    ->category(Factory::CATEGORY_HOTEL)
    ->route('hotels')
    ->model(\App\Admin\Models\Hotel\Hotel::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\HotelController::class)
    ->titles([
        'index' => 'Отели',
        'create' => 'Новый отель'
    ])
    ->views([
        'form' => 'hotel.edit.edit',
        'show' => 'hotel.show.show'
    ])
    ->priority(100);
