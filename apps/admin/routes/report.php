<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('report-order')
    ->post('/generate', Controllers\Report\OrderController::class . '@generate', 'create', 'generate');

AclRoute::for('report-hotel-booking')
    ->post('/generate', Controllers\Report\HotelBookingController::class . '@generate', 'create', 'generate');

AclRoute::for('report-service-booking')
    ->post('/generate', Controllers\Report\ServiceBookingController::class . '@generate', 'create', 'generate');

