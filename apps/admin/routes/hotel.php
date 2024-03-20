<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel')
    ->addAction(['GET', 'POST'], '/search', Controllers\Hotel\HotelController::class . '@search', 'read', 'search')
    ->get('/{hotel}/get', Controllers\Hotel\HotelController::class . '@get', 'read', 'get')
    ->get('/{hotel}/settings/get', Controllers\Hotel\HotelController::class . '@settings', 'read', 'settings')
    ->put('/{hotel}/settings', Controllers\Hotel\HotelController::class . '@updateSettings', 'update', 'settings.update')
    ->get('/{hotel}/rooms/list', Controllers\Hotel\HotelController::class . '@getRooms', 'read', 'rooms.list')
    ->addAction(['GET', 'POST'], '/rooms/search', Controllers\Hotel\RoomController::class . '@search', 'read', 'rooms.search')
    ->get('/rooms/names/{lang}/list', Controllers\Hotel\RoomController::class . '@getRoomNames', 'read', 'rooms.names.list')
    ->get('/{hotel}/rooms/{room}/get', Controllers\Hotel\RoomController::class . '@get', 'read', 'get')
    ->put('/{hotel}/rooms/position', Controllers\Hotel\RoomController::class . '@position', 'update', 'rooms.position')
    ->resource('rooms', Controllers\Hotel\RoomController::class, [
        'parameters' => [
            'rooms' => 'room',
        ],
        'except' => ['show']
    ])

    ->get('/{hotel}/bookings', Controllers\Hotel\BookingController::class . '@index', 'read', 'bookings.index')

    ->resource('prices', Controllers\Hotel\PriceController::class, ['except' => ['show']])
    ->get('/{hotel}/seasons/prices',Controllers\Hotel\PriceController::class.'@getSeasonsPrices','read','prices.seasons.get')
    ->put('/{hotel}/seasons/{season}/prices',Controllers\Hotel\PriceController::class.'@updateSeasonPrice','update','prices.seasons.update')
    ->get('/{hotel}/seasons/{season}/prices/date',Controllers\Hotel\PriceController::class.'@getDatePrices','read','prices.seasons.date.get')
    ->put('/{hotel}/seasons/{season}/prices/date',Controllers\Hotel\PriceController::class.'@updateDatePrice','update','prices.seasons.date.update')
    ->put('/{hotel}/seasons/{season}/prices/date/batch',Controllers\Hotel\PriceController::class.'@batchUpdateDatePrice','update','prices.seasons.date.update.batch')
    ->resource('contacts', Controllers\Hotel\ContactController::class, ['except' => ['show', 'index']])

    ->get('/{hotel}/notes', Controllers\Hotel\NotesController::class . '@edit', 'update', 'notes.edit')
    ->post('/{hotel}/notes', Controllers\Hotel\NotesController::class . '@update', 'update', 'notes.update')

    ->resource('employee', Controllers\Hotel\EmployeeController::class, ['except' => ['show']])
    ->resource('employee.contacts', Controllers\Hotel\EmployeeContactController::class, ['except' => ['show']])

    ->get('/{hotel}/services', Controllers\Hotel\ServiceController::class . '@index', 'read', 'services.index')
    ->put('/{hotel}/services', Controllers\Hotel\ServiceController::class . '@update', 'update', 'services.update')
    ->get('/{hotel}/usabilities', Controllers\Hotel\UsabilityController::class . '@index', 'read', 'usabilities.index')
    ->put('/{hotel}/usabilities', Controllers\Hotel\UsabilityController::class . '@update', 'update', 'usabilities.update')

    ->resource('landmark', Controllers\Hotel\LandmarkController::class, ['only' => ['create', 'store', 'destroy']])

    ->get('/{hotel}/users', Controllers\Hotel\UserController::class . '@createDialog', 'create', 'users.create.dialog')
    ->post('/{hotel}/users', Controllers\Hotel\UserController::class . '@storeDialog', 'create', 'users.store.dialog')
    ->get('/{hotel}/users/{user}/edit', Controllers\Hotel\UserController::class . '@editDialog', 'update', 'users.edit.dialog')
    ->put('/{hotel}/users/{user}', Controllers\Hotel\UserController::class . '@updateDialog', 'update', 'users.update.dialog')
    ->delete('/{hotel}/users/{user}', Controllers\Hotel\UserController::class . '@destroyDialog', 'delete', 'users.destroy.dialog')

    ->get('/{hotel}/images', Controllers\Hotel\ImageController::class . '@index', 'update', 'images.index')
    ->get('/{hotel}/images/get', Controllers\Hotel\ImageController::class . '@get', 'update', 'images.get')
    ->post('/{hotel}/images/upload', Controllers\Hotel\ImageController::class . '@upload', 'update', 'images.upload')
    ->delete('/{hotel}/images/{image}', Controllers\Hotel\ImageController::class . '@destroy', 'update', 'images.destroy')
    ->post('/{hotel}/images/reorder', Controllers\Hotel\ImageController::class . '@reorder', 'update', 'images.reorder')
    ->get('/{hotel}/images/{image}/rooms', Controllers\Hotel\ImageController::class . '@getImageRooms', 'update', 'images.rooms')
    ->post('/{hotel}/images/{image}/main/set', Controllers\Hotel\ImageController::class . '@setMainImage', 'update', 'images.main.set')
    ->post('/{hotel}/images/{image}/main/unset', Controllers\Hotel\ImageController::class . '@unsetMainImage', 'update', 'images.main.unset')

    ->get('/{hotel}/images/{room}/list', Controllers\Hotel\ImageController::class . '@getRoomImages', 'update', 'images.room.get')
    ->post('/{hotel}/rooms/{room}/images/{image}/set', Controllers\Hotel\ImageController::class . '@setRoomImage', 'update', 'images.room.set')
    ->post('/{hotel}/rooms/{room}/images/{image}/unset', Controllers\Hotel\ImageController::class . '@unsetRoomImage', 'update', 'images.room.unset')
    ->post('/{hotel}/rooms/{room}/images/reorder', Controllers\Hotel\ImageController::class . '@reorderRoomImages', 'update', 'images.room.reorder')

    ->get('/quotas/availability', Controllers\Hotel\QuotaAvailabilityController::class . '@index', 'read', 'quotas.availability.index')
    ->post('/quotas/availability', Controllers\Hotel\QuotaAvailabilityController::class . '@get', 'read', 'quotas.availability.get')
    ->get('/{hotel}/quotas', Controllers\Hotel\QuotaController::class . '@index', 'read', 'quotas.index')
    ->post('/{hotel}/quotas', Controllers\Hotel\QuotaController::class . '@get', 'read', 'quotas.get')
    ->put('/{hotel}/quotas/date/batch',Controllers\Hotel\QuotaController::class . '@batchUpdateDateQuota','update','quotas.date.update.batch')
    ->put('/{hotel}/rooms/{room}/quota', Controllers\Hotel\QuotaController::class . '@update', 'update', 'quotas.update')
    ->put('/{hotel}/rooms/{room}/quota/open', Controllers\Hotel\QuotaController::class . '@openQuota', 'update', 'quotas.open')
    ->put('/{hotel}/rooms/{room}/quota/close', Controllers\Hotel\QuotaController::class . '@closeQuota', 'update', 'quotas.close')
    ->put('/{hotel}/rooms/{room}/quota/reset', Controllers\Hotel\QuotaController::class . '@resetQuota', 'update', 'quotas.reset')

    ->get('/{hotel}/settings', Controllers\Hotel\SettingsController::class . '@index', 'update', 'settings.index')
    ->resource('rules', Controllers\Hotel\RuleController::class, ['except' => ['index', 'show']])

    ->resource('contracts', Controllers\Hotel\ContractController::class)
    ->get('/{hotel}/contracts/{contract}/get', Controllers\Hotel\ContractController::class . '@get','read', 'contracts.get')

    ->resource('seasons', Controllers\Hotel\SeasonController::class)
    ->resource('rates', Controllers\Hotel\RateController::class, ['except' => ['show']])
    ->get('/{hotel}/rates/search', Controllers\Hotel\RateController::class . '@search',null, 'rates.search')

    ->get('/{hotel}/settings/markup', Controllers\Hotel\MarkupSettingsController::class . '@get',null, 'settings.markup.get')
    ->put('/{hotel}/settings/markup', Controllers\Hotel\MarkupSettingsController::class . '@update','update', 'settings.markup.update')
    ->post('/{hotel}/settings/markup/condition', Controllers\Hotel\MarkupSettingsController::class . '@addCondition','update', 'settings.markup.condition.add')
    ->delete('/{hotel}/settings/markup/condition', Controllers\Hotel\MarkupSettingsController::class . '@deleteCondition','update', 'settings.markup.condition.delete')

    ->resource('reviews', Controllers\Hotel\ReviewController::class, [
        'parameters' => [
            'reviews' => 'review',
        ],
        'except' => ['show']
    ])
    ;
