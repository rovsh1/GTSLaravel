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
    ->post('/{booking}/price/recalculate', Controllers\Booking\Service\BookingController::class . '@recalculatePrices', 'update', 'price.recalculate')

    ->get('/{booking}/request/list', Controllers\Booking\RequestController::class . '@getBookingRequests', 'read', 'request.list')
    ->post('/{booking}/request', Controllers\Booking\RequestController::class . '@sendRequest', 'update', 'request.send')
    ->get('/{booking}/request/{request}/file', Controllers\Booking\RequestController::class . '@getFileInfo', 'read', 'request.download')

    ->get('/details/types', Controllers\Booking\Service\DetailsController::class . '@getTypes', 'read', 'details.types.get')
    ->put('/{booking}/details', Controllers\Booking\Service\DetailsController::class . '@updateField', 'create', 'details.field.update')

    ->post('/{booking}/guests/add', Controllers\Booking\Service\DetailsController::class . '@addGuest', 'delete', 'guests.add')
    ->delete('/{booking}/guests', Controllers\Booking\Service\DetailsController::class . '@deleteGuest', 'delete', 'guests.delete')

    ->post('/{booking}/cars/add', Controllers\Booking\Service\CarBidController::class . '@addCarBid', 'create', 'cars.add')
    ->put('/{booking}/cars/{carBidId}', Controllers\Booking\Service\CarBidController::class . '@updateCarBid', 'update', 'cars.update')
    ->delete('/{booking}/cars/{carBidId}', Controllers\Booking\Service\CarBidController::class . '@removeCarBid', 'delete', 'cars.delete')
    ->put('/{booking}/cars/{carBidId}/price', Controllers\Booking\Service\CarBidController::class . '@setCarBidManualPrice', 'update', 'cars.price.update')

    ->post('/{booking}/cars/{carBidId}/guests/add', Controllers\Booking\Service\CarBidController::class . '@addGuest', 'delete', 'car.guests.add')
    ->delete('/{booking}/cars/{carBidId}/guests', Controllers\Booking\Service\CarBidController::class . '@deleteGuest', 'delete', 'car.guests.delete')

    ->get('/{booking}/timeline', Controllers\Booking\Service\TimelineController::class . '@index', 'read', 'timeline');
