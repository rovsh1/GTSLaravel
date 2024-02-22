<?php

namespace App\Admin\Http\Controllers\Hotel\Reference;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class RoomTypeController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-room-type';
    }
}
