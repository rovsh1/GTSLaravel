<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('airport-booking')
    ->get('/status/list', Controllers\Booking\Hotel\BookingController::class . '@getStatuses', 'read', 'status.list');
