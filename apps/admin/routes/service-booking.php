<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;


AclRoute::for('service-booking')
    ->get('/{booking}/get', Controllers\Booking\Service\BookingController::class . '@get', 'read', 'get')
    ->put('/{booking}/note', Controllers\Booking\Service\BookingController::class . '@updateNote', 'update', 'note.update')
    ->put('/{booking}/manager', Controllers\Booking\Service\BookingController::class . '@updateManager', 'update', 'manager.update')
    ->post('/{booking}/copy', Controllers\Booking\Service\BookingController::class . '@copy', 'update', 'copy')
    ->delete('/bulk', Controllers\Booking\Service\BookingController::class . '@bulkDelete', 'delete', 'bulk.delete')

    ->get('/status/list', Controllers\Booking\Service\BookingController::class . '@getStatuses', 'read', 'status.list')
    ->put('/{booking}/status/update', Controllers\Booking\Service\BookingController::class . '@updateStatus', 'update', 'status.update')
    ->get('/{booking}/status/history', Controllers\Booking\Service\BookingController::class . '@getStatusHistory', 'read', 'status.history')

    ->get('/{booking}/actions/available', Controllers\Booking\Service\BookingController::class . '@getAvailableActions', 'read', 'actions.available.get')
    ->put('/{booking}/price', Controllers\Booking\Service\BookingController::class . '@updatePrice', 'update', 'price.update')

    ->get('/{booking}/request/list', Controllers\Booking\RequestController::class . '@getBookingRequests', 'read', 'request.list')
    ->post('/{booking}/request', Controllers\Booking\RequestController::class . '@sendRequest', 'update', 'request.send')
    ->get('/{booking}/request/{request}/file', Controllers\Booking\RequestController::class . '@getFileInfo', 'read', 'request.download')

    ->post("/{booking}/details/{serviceId}/create", Controllers\Booking\Service\DetailsController::class . '@create', 'create', 'details.create');
