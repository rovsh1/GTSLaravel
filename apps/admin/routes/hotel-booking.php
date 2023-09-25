<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel-booking')
    ->get('/status/list', Controllers\Booking\Airport\BookingController::class . '@getStatuses', 'read', 'status.list')
    ->delete('/bulk', Controllers\Booking\Hotel\BookingController::class . '@bulkDelete', 'delete', 'bulk.delete')

    ->get('/{booking}/get', Controllers\Booking\Hotel\BookingController::class . '@get', 'read', 'get')
    ->put('/{booking}/note', Controllers\Booking\Hotel\BookingController::class . '@updateNote', 'update', 'note.update')
    ->put('/{booking}/manager', Controllers\Booking\Hotel\BookingController::class . '@updateManager', 'update', 'manager.update')
    ->post('/{booking}/copy', Controllers\Booking\Hotel\BookingController::class . '@copy', 'update', 'copy')
    ->put('/{booking}/status/update', Controllers\Booking\Airport\BookingController::class . '@updateStatus', 'update', 'status.update')
    ->get('/{booking}/status/history', Controllers\Booking\Airport\BookingController::class . '@getStatusHistory', 'read', 'status.history')
    ->get('/{booking}/actions/available', Controllers\Booking\Hotel\BookingController::class . '@getAvailableActions', 'read', 'actions.available.get')
    ->put('/{booking}/external/number', Controllers\Booking\Hotel\BookingController::class . '@updateExternalNumber', 'update', 'external.number.update')
    ->put('/{booking}/price', Controllers\Booking\Hotel\BookingController::class . '@updatePrice', 'update', 'price.update')

    ->get('/{booking}/rooms/available', Controllers\Booking\Hotel\RoomController::class . '@getAvailable', 'read', 'rooms.available.get')
    ->post('/{booking}/rooms/add', Controllers\Booking\Hotel\RoomController::class . '@addRoom', 'update', 'rooms.add')
    ->put('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@updateRoom', 'update', 'rooms.update')
    ->delete('/{booking}/rooms', Controllers\Booking\Hotel\RoomController::class . '@deleteRoom', 'update', 'rooms.delete')

    ->post('/{booking}/rooms/guests/add', Controllers\Booking\Hotel\RoomController::class . '@addRoomGuest', 'update', 'rooms.guests.add')
    ->delete('/{booking}/rooms/guests', Controllers\Booking\Hotel\RoomController::class . '@deleteRoomGuest', 'delete', 'rooms.guests.delete')
    ->put('/{booking}/rooms/{roomBookingId}/price', Controllers\Booking\Hotel\RoomController::class . '@updatePrice', 'update', 'rooms.price.update')

    ->get('/{booking}/request/list', Controllers\Booking\Hotel\RequestController::class . '@getBookingRequests', 'read', 'request.list')
    ->post('/{booking}/request', Controllers\Booking\Hotel\RequestController::class . '@sendRequest', 'update', 'request.send')
    ->get('/{booking}/request/{request}/file', Controllers\Booking\Hotel\RequestController::class . '@getFileInfo', 'read', 'request.download')

    ->get('/{booking}/voucher/list', Controllers\Booking\Hotel\VoucherController::class . '@getBookingVouchers', 'read', 'voucher.list')
    ->post('/{booking}/voucher', Controllers\Booking\Hotel\VoucherController::class . '@sendVoucher', 'update', 'voucher.send')
    ->get('/{booking}/voucher/{voucher}/file', Controllers\Booking\Hotel\VoucherController::class . '@getFileInfo', 'read', 'voucher.download');
