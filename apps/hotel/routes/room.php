<?php

use App\Hotel\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)
    ->prefix('rooms')
    ->as('rooms.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::get('/rooms/list', Controllers\Hotel\HotelController::class . '@getRooms', 'read', 'rooms.list');
        Route::get(
            '/rooms/names/{lang}/list',
            Controllers\Hotel\RoomController::class . '@getRoomNames',
            'read',
            'rooms.names.list'
        );
        Route::get('/rooms/{room}/get', Controllers\Hotel\RoomController::class . '@get', 'read', 'get');
        Route::put(
            '/rooms/position',
            Controllers\Hotel\RoomController::class . '@position',
            'update',
            'rooms.position'
        );
    });
