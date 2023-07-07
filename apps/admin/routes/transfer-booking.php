<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('transfer-booking')
    ->get('/status/list', Controllers\Booking\Transfer\BookingController::class . '@getStatuses', 'read', 'status.list');
