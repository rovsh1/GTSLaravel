<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('booking-order')
    ->get('/{id}/get', Controllers\Booking\Order\OrderController::class . '@get', 'read', 'get')
    ->get('/search', Controllers\Booking\Order\OrderController::class . '@search', 'read', 'search')

    ->get('/{orderId}/bookings', Controllers\Booking\Order\OrderController::class . '@bookings', 'read', 'guests.list')

    ->get('/status/list', Controllers\Booking\Order\OrderController::class . '@getStatuses', 'read', 'status.list')
    ->put('/{orderId}/status/update', Controllers\Booking\Order\OrderController::class . '@updateStatus', 'update', 'status.update')
    ->get('/{orderId}/actions/available', Controllers\Booking\Order\OrderController::class . '@getAvailableActions', 'read', 'actions.available.get')

    ->get('/{orderId}/guests', Controllers\Booking\Order\GuestController::class . '@list', 'read', 'guests.list')
    ->post('/{orderId}/guests/add', Controllers\Booking\Order\GuestController::class . '@addGuest', 'update', 'guests.add')
    ->put('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@updateGuest', 'update', 'guests.update')
    ->delete('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@deleteGuest', 'update', 'guests.delete');
