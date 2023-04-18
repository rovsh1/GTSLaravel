<?php

use Custom\Framework\Support\Facades\Route;
use Module\Booking\Hotel\Port\Controllers\InfoController;
use Module\Booking\Hotel\Port\Controllers\ReservationController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('searchActiveReservations', [InfoController::class, 'searchActiveReservations']);
Route::register('searchUpdatedReservations', [InfoController::class, 'searchUpdatedReservations']);

//FIXME TEST
Route::register('booking-cancel', [ReservationController::class, 'cancel']);
