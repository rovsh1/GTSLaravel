<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel')
    ->get('/{hotel}/get', Controllers\Hotel\HotelController::class . '@get', 'read', 'get')
    ->get('/{hotel}/rooms/list', Controllers\Hotel\HotelController::class . '@getRooms', 'read', 'rooms.list')

    ->get('/{hotel}/rooms/{room}/get', Controllers\Hotel\RoomController::class . '@get', 'read', 'get')
    ->put('/{hotel}/rooms/position', Controllers\Hotel\RoomController::class . '@position', 'update', 'rooms.position')
    ->resource('rooms', Controllers\Hotel\RoomController::class, [
        'parameters' => [
            'rooms' => 'room',
        ],
        'except' => ['show']
    ])

    ->resource('prices', Controllers\Hotel\PriceController::class, ['except' => ['show']])
    ->resource('contacts', Controllers\Hotel\ContactController::class, ['except' => ['show', 'index']])

    ->get('/{hotel}/notes', Controllers\Hotel\NotesController::class . '@edit', 'update', 'notes.edit')
    ->put('/{hotel}/notes', Controllers\Hotel\NotesController::class . '@update', 'update', 'notes.update')

    ->resource('employee', Controllers\Hotel\EmployeeController::class, ['except' => ['show']])
    ->resource('employee.contacts', Controllers\Hotel\EmployeeContactController::class, ['except' => ['show']])

    ->get('/{hotel}/services', Controllers\Hotel\ServiceController::class . '@index', 'read', 'services.index')
    ->put('/{hotel}/services', Controllers\Hotel\ServiceController::class . '@update', 'update', 'services.update')
    ->get('/{hotel}/usabilities', Controllers\Hotel\UsabilityController::class . '@index', 'read', 'usabilities.index')
    ->put('/{hotel}/usabilities', Controllers\Hotel\UsabilityController::class . '@update', 'update', 'usabilities.update')

    ->resource('landmark', Controllers\Hotel\LandmarkController::class, ['only' => ['create', 'store', 'destroy']])

    ->get('/{hotel}/images', Controllers\Hotel\ImageController::class . '@index', 'update', 'images.index')
    ->get('/{hotel}/images/get', Controllers\Hotel\ImageController::class . '@get', 'update', 'images.get')
    ->post('/{hotel}/images/upload', Controllers\Hotel\ImageController::class . '@upload', 'update', 'images.upload')
    ->delete('/{hotel}/images/{image}', Controllers\Hotel\ImageController::class . '@destroy', 'update', 'images.destroy')
    ->post('/{hotel}/images/reorder', Controllers\Hotel\ImageController::class . '@reorder', 'update', 'images.reorder')

    ->get('/{hotel}/images/{room}/list', Controllers\Hotel\ImageController::class . '@getRoomImages', 'update', 'images.room.get')
    ->post('/{hotel}/rooms/{room}/images/{image}/set', Controllers\Hotel\ImageController::class . '@setRoomImage', 'update', 'images.room.set')
    ->post('/{hotel}/rooms/{room}/images/{image}/unset', Controllers\Hotel\ImageController::class . '@unsetRoomImage', 'update', 'images.room.unset')

    ->get('/{hotel}/quotas', Controllers\Hotel\QuotaController::class . '@index', 'read', 'quotas.index')
    ->post('/{hotel}/quotas', Controllers\Hotel\QuotaController::class . '@get', 'read', 'quotas.get')
    ->put('/{hotel}/rooms/{room}/quota', Controllers\Hotel\QuotaController::class . '@update', 'update', 'quotas.update')
    ->put('/{hotel}/rooms/{room}/quota/open', Controllers\Hotel\QuotaController::class . '@openQuota', 'update', 'quotas.open')
    ->put('/{hotel}/rooms/{room}/quota/close', Controllers\Hotel\QuotaController::class . '@closeQuota', 'update', 'quotas.close')
    ->put('/{hotel}/rooms/{room}/quota/reset', Controllers\Hotel\QuotaController::class . '@resetQuota', 'update', 'quotas.reset')

    ->get('/{hotel}/settings', Controllers\Hotel\SettingsController::class . '@index', 'update', 'settings.index')
    ->resource('rules', Controllers\Hotel\RuleController::class, ['except' => ['index', 'show']])

    ->resource('contracts', Controllers\Hotel\ContractController::class)
    ->get('/{hotel}/contracts/{contract}/get', Controllers\Hotel\ContractController::class . '@get','update', 'contracts.get')

    ->resource('seasons', Controllers\Hotel\SeasonController::class)
    ->resource('rates', Controllers\Hotel\RateController::class)

    ->get('/{hotel}/settings/markup', Controllers\Hotel\MarkupSettingsController::class . '@get','read', 'settings.markup.get')
    ->put('/{hotel}/settings/markup', Controllers\Hotel\MarkupSettingsController::class . '@update','update', 'settings.markup.update')
    ->post('/{hotel}/settings/markup/condition', Controllers\Hotel\MarkupSettingsController::class . '@addCondition','update', 'settings.markup.condition.add')
    ->delete('/{hotel}/settings/markup/condition', Controllers\Hotel\MarkupSettingsController::class . '@deleteCondition','update', 'settings.markup.condition.delete')
    ;
