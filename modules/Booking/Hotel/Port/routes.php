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
Route::register('getBookingDetails', [AdminController::class, 'getBookingDetails']);
Route::register('createBooking', [AdminController::class, 'createBooking']);
Route::register('addRoom', [AdminController::class, 'addRoom']);
Route::register('updateRoom', [AdminController::class, 'updateRoom']);
Route::register('deleteRoom', [AdminController::class, 'deleteRoom']);
Route::register('addRoomGuest', [AdminController::class, 'addRoomGuest']);
Route::register('updateRoomGuest', [AdminController::class, 'updateRoomGuest']);

//FIXME TEST
Route::register('booking-cancel', [ReservationController::class, 'cancel']);
