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

    ->get('/{orderId}/guests', Controllers\Booking\Order\GuestController::class . '@list', 'read', 'guests.list')
    ->post('/{orderId}/guests/add', Controllers\Booking\Order\GuestController::class . '@addGuest', 'update', 'guests.add')
    ->put('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@updateGuest', 'update', 'guests.update')
    ->delete('/{orderId}/guests/{guestId}', Controllers\Booking\Order\GuestController::class . '@deleteGuest', 'update', 'guests.delete')

    ->get('/{orderId}/invoice', Controllers\Booking\Order\InvoiceController::class . '@get', 'read', 'invoice.get')
    ->post('/{orderId}/invoice', Controllers\Booking\Order\InvoiceController::class . '@create', 'create', 'invoice.create')
    ->post('/{orderId}/invoice/cancel', Controllers\Booking\Order\InvoiceController::class . '@cancel', 'update', 'invoice.cancel')
    ->post('/{orderId}/invoice/send', Controllers\Booking\Order\InvoiceController::class . '@send', 'update', 'invoice.send')
    ->get('/{orderId}/invoice/file', Controllers\Booking\Order\InvoiceController::class . '@getFile', 'read', 'invoice.file')

    ->get('/{booking}/voucher/list', Controllers\Booking\Order\VoucherController::class . '@getBookingVouchers', 'read', 'voucher.list')
    ->post('/{booking}/voucher', Controllers\Booking\Order\VoucherController::class . '@sendVoucher', 'update', 'voucher.send')
    ->get('/{booking}/voucher/{voucher}/file', Controllers\Booking\Order\VoucherController::class . '@getFileInfo', 'read', 'voucher.download');
