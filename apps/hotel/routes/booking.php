<?php

use App\Hotel\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::controller(BookingController::class)
    ->prefix('booking')
    ->as('booking.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{booking}', 'show')->name('show');
        Route::get('/{booking}/timeline', 'timeline')->name('timeline');
    });
