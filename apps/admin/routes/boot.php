<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Admin\Http\Controllers\DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::group([], __DIR__ . '/auth.php');
Route::group([], __DIR__ . '/administrator.php');
Route::group([], __DIR__ . '/mail.php');
Route::group([], __DIR__ . '/hotel.php');
Route::group([], __DIR__ . '/service-provider.php');
Route::group([], __DIR__ . '/file-manager.php');
Route::group([], __DIR__ . '/city.php');
Route::group([], __DIR__ . '/client.php');
Route::group([], __DIR__ . '/country.php');
Route::group([], __DIR__ . '/airport.php');
Route::group([], __DIR__ . '/hotel-booking.php');
Route::group([], __DIR__ . '/airport-booking.php');
Route::group([], __DIR__ . '/transfer-booking.php');
Route::group([], __DIR__ . '/booking-order.php');
Route::group([], __DIR__ . '/currency.php');
