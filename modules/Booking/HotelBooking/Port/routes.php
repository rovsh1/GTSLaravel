<?php

use Module\Booking\HotelBooking\Port\Controllers\InfoController;
use Module\Booking\HotelBooking\Port\Controllers\ReservationController;
use Sdk\Module\Support\Route;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('searchActiveReservations', [InfoController::class, 'searchActiveReservations']);
Route::register('searchUpdatedReservations', [InfoController::class, 'searchUpdatedReservations']);

//FIXME TEST
Route::register('booking-cancel', [ReservationController::class, 'cancel']);
