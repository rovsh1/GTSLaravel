<?php

use App\Admin\Components\Factory\Factory;

return Factory::key('hotel-room-name')
    ->category(Factory::CATEGORY_HOTEL)
    ->group(Factory::GROUP_SERVICE)
    ->model(\App\Admin\Models\Hotel\Reference\RoomName::class)
    ->controller(\App\Admin\Http\Controllers\Hotel\Reference\RoomNameController::class, ['except' => ['show']])
    ->titles([
        "index" => "Наименования номеров отеля",
        "create" => "Новое наименование"
    ]);