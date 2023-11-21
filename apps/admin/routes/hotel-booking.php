<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel-booking')
    ->put('/{booking}/external/number', Controllers\Booking\Hotel\BookingController::class . '@updateExternalNumber', 'update', 'external.number.update')

    ->get('/{booking}/rooms/available', Controllers\Booking\Hotel\RoomController::class . '@getAvailable', 'read', 'rooms.available.get')
    ->post('/{booking}/rooms/add', Controllers\Booking\Hotel\RoomController::class . '@addRoom', 'update', 'rooms.add')
    ->put('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@updateRoom', 'update', 'rooms.update')
    ->delete('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@deleteRoom', 'update', 'rooms.delete')

    ->post('/{booking}/rooms/guests/add', Controllers\Booking\Hotel\RoomController::class . '@addRoomGuest', 'update', 'rooms.guests.add')
    ->delete('/{booking}/rooms/guests', Controllers\Booking\Hotel\RoomController::class . '@deleteRoomGuest', 'delete', 'rooms.guests.delete')
    ->put('/{booking}/rooms/{roomBookingId}/price', Controllers\Booking\Hotel\RoomController::class . '@updatePrice', 'update', 'rooms.price.update');

