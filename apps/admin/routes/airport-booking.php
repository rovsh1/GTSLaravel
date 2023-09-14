<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('airport-booking')
    ->get('/{booking}/get', Controllers\Booking\Airport\BookingController::class . '@get', 'read', 'get')

    ->get('/status/list', Controllers\Booking\Airport\BookingController::class . '@getStatuses', 'read', 'status.list')
    ->put('/{booking}/status/update', Controllers\Booking\Airport\BookingController::class . '@updateStatus', 'update', 'status.update')
    ->get('/{booking}/status/history', Controllers\Booking\Airport\BookingController::class . '@getStatusHistory', 'read', 'status.history')

    ->get('/{booking}/actions/available', Controllers\Booking\Airport\BookingController::class . '@getAvailableActions', 'read', 'actions.available.get')

    ->delete('/{booking}/guests', Controllers\Booking\Airport\GuestController::class . '@deleteGuest', 'delete', 'guests.delete')

    ->get('/{booking}/request/list', Controllers\Booking\Airport\RequestController::class . '@getBookingRequests', 'read', 'request.list')
    ->post('/{booking}/request', Controllers\Booking\Airport\RequestController::class . '@sendRequest', 'update', 'request.send')
    ->get('/{booking}/request/{request}/file', Controllers\Booking\Airport\RequestController::class . '@getFileInfo', 'read', 'request.download')
;
