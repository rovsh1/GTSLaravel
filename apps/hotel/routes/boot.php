<?php

use App\Hotel\Http\Controllers\DashboardController;
use App\Hotel\Http\Middleware\HotelContextMiddleware;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });


Route::middleware(HotelContextMiddleware::class)->group(function () {
    Route::group([], __DIR__ . '/auth.php');
    Route::group([], __DIR__ . '/profile.php');
    Route::group([], __DIR__ . '/booking.php');
    Route::group([], __DIR__ . '/room.php');
    Route::group([], __DIR__ . '/image.php');
    Route::group([], __DIR__ . '/hotel.php');
});
