<?php

use Module\Booking\Hotel\Port\Controllers\AdminController;
use Module\Booking\Hotel\Port\Controllers\InfoController;
use Module\Booking\Hotel\Port\Controllers\ReservationController;
use Sdk\Module\Support\Route;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('searchActiveReservations', [InfoController::class, 'searchActiveReservations']);
Route::register('searchUpdatedReservations', [InfoController::class, 'searchUpdatedReservations']);

Route::register('getBookingDetails', [AdminController::class, 'getBookingDetails']);
Route::register('addRoom', [AdminController::class, 'addRoom']);
Route::register('updateRoom', [AdminController::class, 'updateRoom']);
Route::register('deleteRoom', [AdminController::class, 'deleteRoom']);
Route::register('addRoomGuest', [AdminController::class, 'addRoomGuest']);
Route::register('updateRoomGuest', [AdminController::class, 'updateRoomGuest']);

//FIXME TEST
Route::register('booking-cancel', [ReservationController::class, 'cancel']);
