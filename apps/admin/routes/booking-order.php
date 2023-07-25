<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('booking-order')
    ->get('/search', Controllers\Booking\Order\OrderController::class . '@search', 'read', 'search');
