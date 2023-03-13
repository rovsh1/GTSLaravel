<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class RoomNameController extends AbstractEnumController
{
    protected function getPrototypeKey(): string
    {
        return 'hotel-room-name';
    }
}
