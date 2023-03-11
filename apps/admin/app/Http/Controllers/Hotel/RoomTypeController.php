<?php

namespace App\Admin\Http\Controllers\Hotel;

use App\Admin\Support\Http\Controllers\AbstractEnumController;

class RoomTypeController extends AbstractEnumController
{
    protected $prototype = 'hotel-room-type';
}
