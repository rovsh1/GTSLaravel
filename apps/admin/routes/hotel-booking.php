<?php

use App\Admin\Http\Controllers\Booking\Hotel\BookingController;
use App\Admin\Http\Controllers\Booking\Hotel\RoomController;
use App\Admin\Http\Controllers\Booking\Hotel\TimelineController;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel-booking')
    ->put(
        '/{booking}/external/number',
        BookingController::class . '@updateExternalNumber',
        'update',
        'external.number.update'
    )
    ->get('/{booking}/rooms/available', RoomController::class . '@getAvailable', 'read', 'rooms.available.get')
    ->post('/{booking}/rooms/add', RoomController::class . '@addRoom', 'update', 'rooms.add')
    ->put('/{booking}/rooms', RoomController::class . '@updateRoom', 'update', 'rooms.update')
    ->delete('/{booking}/rooms', RoomController::class . '@deleteRoom', 'update', 'rooms.delete')
    ->post('/{booking}/rooms/guests/add', RoomController::class . '@addRoomGuest', 'update', 'rooms.guests.add')
    ->delete('/{booking}/rooms/guests', RoomController::class . '@deleteRoomGuest', 'delete', 'rooms.guests.delete')
    ->put(
        '/{booking}/rooms/{roomBookingId}/price',
        RoomController::class . '@updatePrice',
        'update',
        'rooms.price.update'
    )
    ->get('/{booking}/timeline', TimelineController::class . '@index', 'read', 'timeline');

