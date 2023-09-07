<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('airport-booking')
    ->get('/status/list', Controllers\Booking\Airport\BookingController::class . '@getStatuses', 'read', 'status.list')
    ->get('/{booking}/get', Controllers\Booking\Airport\BookingController::class . '@get', 'read', 'get')
    ->delete('/{booking}/guests', Controllers\Booking\Airport\GuestController::class . '@deleteGuest', 'delete', 'guests.delete')
;
