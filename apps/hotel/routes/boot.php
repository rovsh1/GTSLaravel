<?php

use App\Hotel\Http\Controllers\BookingController;
use App\Hotel\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::controller(BookingController::class)
    ->prefix('booking')
    ->as('booking.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

Route::group([], __DIR__ . '/auth.php');
