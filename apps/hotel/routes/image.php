<?php

use App\Hotel\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::controller(ImageController::class)
    ->prefix('img')
    ->as('images.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::get('/get', 'get')->name('get');
        Route::post('/upload', 'upload')->name('upload');
        Route::delete('/{image}', 'destroy')->name('destroy');
        Route::post('/reorder', 'reorder')->name('reorder');
        Route::get('/{image}/rooms', 'getImageRooms')->name('rooms');
        Route::post('/{image}/main/set', 'setMainImage')->name('main.set');
        Route::post('/{image}/main/unset', 'unsetMainImage')->name('main.unset');

        Route::get('/{room}/list', 'getRoomImages')->name('room.get');
        Route::post('/rooms/{room}/images/{image}/set', 'setRoomImage')->name('room.set');
        Route::post('/rooms/{room}/images/{image}/unset', 'unsetRoomImage')->name('room.unset');
        Route::post('/rooms/{room}/images/reorder', 'reorderRoomImages')->name('room.reorder');
    });
