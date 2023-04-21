<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('hotel')
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
    ->get('/{hotel}/images/list', Controllers\Hotel\ImageController::class . '@get', 'update', 'images.get')
    ->post('/{hotel}/images/upload', Controllers\Hotel\ImageController::class . '@upload', 'update', 'images.upload')
    ->delete('/{hotel}/images/{image}', Controllers\Hotel\ImageController::class . '@destroy', 'update', 'images.destroy')
    ->post('/{hotel}/images/reorder', Controllers\Hotel\ImageController::class . '@reorder', 'update', 'images.reorder');
