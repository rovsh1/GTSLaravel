<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('booking-order')
    ->get('/search', Controllers\Booking\Order\OrderController::class . '@search', 'read', 'search')
    ->get('/{orderId}/guests', Controllers\Booking\Order\GuestController::class . '@list', 'read', 'guests.list')
    ->post('/{orderId}/guests/add', Controllers\Booking\Order\GuestController::class . '@addGuest', 'update', 'guests.add')
    ->put('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@updateGuest', 'update', 'guests.update')
    ->delete('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@deleteGuest', 'update', 'guests.delete');
