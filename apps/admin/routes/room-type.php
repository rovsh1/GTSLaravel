<?php

use App\Admin\Http\Controllers\Hotel\Reference\RoomTypeController;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel-room-type')
    ->get('/list', RoomTypeController::class . '@list', 'read', 'list');