<?php

use App\Hotel\Http\Controllers\HotelController;
use App\Hotel\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)
    ->prefix('rooms')
    ->as('rooms.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::get('/list', [HotelController::class, 'getRooms'])->name('list');
        Route::get('/{room}/edit', 'edit')->name('edit');
        Route::get('/{room}/get', 'get')->name('get');
        Route::put('/position', 'position')->name('position');
    });
