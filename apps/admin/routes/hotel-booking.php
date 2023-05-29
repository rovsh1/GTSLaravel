<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel-booking')
    ->get('/status/list', Controllers\Booking\Hotel\BookingController::class . '@getStatuses', 'read', 'status.list')

    ->get('/{booking}/get', Controllers\Booking\Hotel\BookingController::class . '@get', 'read', 'get')
    ->get('/{booking}/details', Controllers\Booking\Hotel\BookingController::class . '@getDetails', 'read', 'details.get')
    ->get('/{booking}/status/available', Controllers\Booking\Hotel\BookingController::class . '@getAvailableStatuses', 'read', 'status.get')

    ->post('/{booking}/rooms/add', Controllers\Booking\Hotel\RoomController::class . '@addRoom', 'update', 'rooms.add')
    ->put('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@updateRoom', 'update', 'rooms.update')
    ->delete('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@deleteRoom', 'update', 'rooms.delete')

    ->post('/{booking}/rooms/guests/add', Controllers\Booking\Hotel\RoomController::class . '@addRoomGuest', 'update', 'rooms.guests.add')
    ->put('/{booking}/rooms/guests', Controllers\Booking\Hotel\RoomController::class . '@updateRoomGuest', 'update', 'rooms.guests.update');
