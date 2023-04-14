<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

// hotel
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
    ->get('/{hotel}/services', Controllers\Hotel\ServiceController::class . '@index', 'read', 'services.index')
    ->put(
        '/{hotel}/services',
        Controllers\Hotel\ServiceController::class . '@update',
        'update',
        'services.update'
    )
    ->get('/{hotel}/usabilities', Controllers\Hotel\UsabilityController::class . '@index', 'read', 'usabilities.index')
    ->put(
        '/{hotel}/usabilities',
        Controllers\Hotel\UsabilityController::class . '@update',
        'update',
        'usabilities.update'
    );


AclRoute::for('service-provider')
    ->resource('contacts', Controllers\ServiceProvider\ContactController::class, ['except' => ['show', 'index']]);


