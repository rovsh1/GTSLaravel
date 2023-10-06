<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('transfer-booking')
    ->get('/{booking}/get', Controllers\Booking\Transfer\BookingController::class . '@get', 'read', 'get')
    ->put('/{booking}/note', Controllers\Booking\Transfer\BookingController::class . '@updateNote', 'update', 'note.update')
    ->put('/{booking}/manager', Controllers\Booking\Transfer\BookingController::class . '@updateManager', 'update', 'manager.update')
    ->post('/{booking}/copy', Controllers\Booking\Transfer\BookingController::class . '@copy', 'update', 'copy')
    ->delete('/bulk', Controllers\Booking\Transfer\BookingController::class . '@bulkDelete', 'delete', 'bulk.delete')

    ->get('/status/list', Controllers\Booking\Transfer\BookingController::class . '@getStatuses', 'read', 'status.list')
    ->put('/{booking}/status/update', Controllers\Booking\Transfer\BookingController::class . '@updateStatus', 'update', 'status.update')
    ->get('/{booking}/status/history', Controllers\Booking\Transfer\BookingController::class . '@getStatusHistory', 'read', 'status.history')

    ->get('/{booking}/actions/available', Controllers\Booking\Transfer\BookingController::class . '@getAvailableActions', 'read', 'actions.available.get')
    ->put('/{booking}/price', Controllers\Booking\Transfer\BookingController::class . '@updatePrice', 'update', 'price.update')

    ->get('/{booking}/request/list', Controllers\Booking\Transfer\RequestController::class . '@getBookingRequests', 'read', 'request.list')
    ->post('/{booking}/request', Controllers\Booking\Transfer\RequestController::class . '@sendRequest', 'update', 'request.send')
    ->get('/{booking}/request/{request}/file', Controllers\Booking\Transfer\RequestController::class . '@getFileInfo', 'read', 'request.download');
