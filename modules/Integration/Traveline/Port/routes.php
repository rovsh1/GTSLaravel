<?php

use Module\Integration\Traveline\Port\Controllers\HotelController;
use Module\Integration\Traveline\Port\Controllers\ReservationController;
use Sdk\Module\Support\Route;

Route::register('update', [HotelController::class, 'update']);
Route::register('getRoomsAndRatePlans', [HotelController::class, 'getRoomsAndRatePlans']);

Route::register('getReservations', [ReservationController::class, 'getReservations']);
Route::register('confirmReservations', [ReservationController::class, 'confirmReservations']);
