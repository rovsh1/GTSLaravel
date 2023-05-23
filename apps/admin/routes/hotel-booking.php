<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel-booking')
    ->resource('rooms', Controllers\Booking\Hotel\RoomController::class, ['only' => ['create', 'store', 'edit', 'update']]);
