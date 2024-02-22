<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-room-type')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Hotel\Reference\RoomType::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\RoomTypeController::class, ['except' => ['show']])
    ->titles([
        "index" => "Типы номеров отеля",
        "create" => "Новый тип"
    ]);