<?php

use Custom\Framework\Support\Facades\Route;
use Module\Booking\Hotel\Port\Controllers\AdminController;
use Module\Booking\Hotel\Port\Controllers\InfoController;
use Module\Booking\Hotel\Port\Controllers\ReservationController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('searchActiveReservations', [InfoController::class, 'searchActiveReservations']);
Route::register('searchUpdatedReservations', [InfoController::class, 'searchUpdatedReservations']);

Route::register('getBookings', [AdminController::class, 'getBookings']);
Route::register('getBooking', [AdminController::class, 'getBooking']);
Route::register('createBooking', [AdminController::class, 'createBooking']);

//FIXME TEST
Route::register('booking-cancel', [ReservationController::class, 'cancel']);
