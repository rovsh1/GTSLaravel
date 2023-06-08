<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel-booking')
    ->get('/status/list', Controllers\Booking\Hotel\BookingController::class . '@getStatuses', 'read', 'status.list')

    ->get('/{booking}/get', Controllers\Booking\Hotel\BookingController::class . '@get', 'read', 'get')
    ->put('/{booking}/status/update', Controllers\Booking\Hotel\BookingController::class . '@updateStatus', 'update', 'status.update')
    ->get('/{booking}/status/history', Controllers\Booking\Hotel\BookingController::class . '@getStatusHistory', 'read', 'status.history')
    ->get('/{booking}/details', Controllers\Booking\Hotel\BookingController::class . '@getDetails', 'read', 'details.get')
    ->get('/{booking}/actions/available', Controllers\Booking\Hotel\BookingController::class . '@getAvailableActions', 'read', 'status.get')
    ->put('/{booking}/external/number', Controllers\Booking\Hotel\BookingController::class . '@updateExternalNumber', 'update', 'external.number.update')

    ->post('/{booking}/rooms/add', Controllers\Booking\Hotel\RoomController::class . '@addRoom', 'update', 'rooms.add')
    ->put('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@updateRoom', 'update', 'rooms.update')
    ->delete('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@deleteRoom', 'update', 'rooms.delete')

    ->post('/{booking}/rooms/guests/add', Controllers\Booking\Hotel\RoomController::class . '@addRoomGuest', 'update', 'rooms.guests.add')
    ->put('/{booking}/rooms/guests', Controllers\Booking\Hotel\RoomController::class . '@updateRoomGuest', 'update', 'rooms.guests.update')

    ->get('/{booking}/request/list', Controllers\Booking\Hotel\RequestController::class . '@getBookingRequests', 'read', 'request.list')
    ->post('/{booking}/request', Controllers\Booking\Hotel\RequestController::class . '@sendRequest', 'update', 'request.send')
    ->get('/{booking}/request/{request}/file', Controllers\Booking\Hotel\RequestController::class . '@getFileInfo', 'read', 'request.download');
