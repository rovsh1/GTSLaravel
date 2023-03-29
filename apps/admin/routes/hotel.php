<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

// hotel
AclRoute::for('hotel')
    ->resource('rooms', Controllers\Hotel\RoomController::class, [
        'parameters' => [
            'rooms' => 'room',
        ],
        'except' => ['show']
    ])
    //->get('/{hotel}/rooms/{room}/text', 'rooms.description', 'update')
    ->resource('prices', Controllers\Hotel\PriceController::class, ['except' => ['show']]);

AclRoute::for('service-provider')
    ->resource('contacts', Controllers\ServiceProvider\ContactController::class, ['except' => ['show', 'index']]);


