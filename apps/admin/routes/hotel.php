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
    ->resource('contacts', Controllers\Hotel\ContactController::class, ['except' => ['show', 'index']]);


AclRoute::for('service-provider')
    ->resource('contacts', Controllers\ServiceProvider\ContactController::class, ['except' => ['show', 'index']]);


