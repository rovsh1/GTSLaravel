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
Route::group([], __DIR__ . '/supplier.php');
Route::group([], __DIR__ . '/city.php');
Route::group([], __DIR__ . '/client.php');
Route::group([], __DIR__ . '/country.php');
Route::group([], __DIR__ . '/airport.php');
Route::group([], __DIR__ . '/hotel-booking.php');
Route::group([], __DIR__ . '/service-booking.php');
Route::group([], __DIR__ . '/booking-order.php');
Route::group([], __DIR__ . '/currency.php');
Route::group([], __DIR__ . '/locale-dictionary.php');
Route::group([], __DIR__ . '/markup-group.php');
Route::group([], __DIR__ . '/railway-station.php');
Route::group([], __DIR__ . '/payment.php');
