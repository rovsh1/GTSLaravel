<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('booking-order')
    ->get('/search', Controllers\Booking\Order\OrderController::class . '@search', 'read', 'search')
    ->get('/{orderId}/tourists', Controllers\Booking\Order\TouristController::class . '@list', 'read', 'tourists.list')
    ->post('/{orderId}/tourists/add', Controllers\Booking\Order\TouristController::class . '@addTourist', 'update', 'tourists.add')
    ->put('/{orderId}/tourists/{touristId}', Controllers\Booking\Order\TouristController::class . '@updateTourist', 'update', 'tourists.update')
    ->delete('/{orderId}/tourists/{touristId}', Controllers\Booking\Order\TouristController::class . '@deleteTourist', 'update', 'tourists.delete');
