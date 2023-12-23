<?php

use App\Hotel\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)
    ->prefix('rooms')
    ->as('rooms.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
//        Route::get('/{booking}', 'show')->name('show');
    });
