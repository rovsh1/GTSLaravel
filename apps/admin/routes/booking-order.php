<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('booking-order')
    ->get('/{id}/get', Controllers\Booking\Order\OrderController::class . '@get', 'read', 'get')
    ->get('/search', Controllers\Booking\Order\OrderController::class . '@search', 'read', 'search')

    ->get('/{orderId}/bookings', Controllers\Booking\Order\OrderController::class . '@bookings', 'read', 'booking.list')

    ->get('/status/list', Controllers\Booking\Order\OrderController::class . '@getStatuses', 'read', 'status.list')
    ->put('/{orderId}/status/update', Controllers\Booking\Order\OrderController::class . '@updateStatus', 'update', 'status.update')
    ->get('/{orderId}/actions/available', Controllers\Booking\Order\OrderController::class . '@getAvailableActions', 'read', 'actions.available.get')

    ->put('/{orderId}/note', Controllers\Booking\Order\OrderController::class . '@updateNote', 'update', 'note.update')
    ->put('/{orderId}/external-id', Controllers\Booking\Order\OrderController::class . '@updateExternalId', 'update', 'externalId.update')
    ->put('/{orderId}/penalty', Controllers\Booking\Order\OrderController::class . '@updateClientPenalty', 'update', 'penalty.update')

    ->get('/{orderId}/guests', Controllers\Booking\Order\GuestController::class . '@list', 'read', 'guests.list')
    ->post('/{orderId}/guests/add', Controllers\Booking\Order\GuestController::class . '@addGuest', 'update', 'guests.add')
    ->put('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@updateGuest', 'update', 'guests.update')
    ->delete('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@deleteGuest', 'update', 'guests.delete')

    ->get('/{orderId}/invoice', Controllers\Booking\Order\InvoiceController::class . '@get', 'read', 'invoice.get')
    ->post('/{orderId}/invoice', Controllers\Booking\Order\InvoiceController::class . '@create', 'create', 'invoice.create')
    ->post('/{orderId}/invoice/cancel', Controllers\Booking\Order\InvoiceController::class . '@cancel', 'update', 'invoice.cancel')
    ->post('/{orderId}/invoice/send', Controllers\Booking\Order\InvoiceController::class . '@send', 'update', 'invoice.send')
    ->get('/{orderId}/invoice/file', Controllers\Booking\Order\InvoiceController::class . '@getFile', 'read', 'invoice.file')

    ->post('/{orderId}/voucher', Controllers\Booking\Order\VoucherController::class . '@create', 'update', 'voucher.create')
    ->post('/{orderId}/voucher/send', Controllers\Booking\Order\VoucherController::class . '@send', 'update', 'voucher.send');
