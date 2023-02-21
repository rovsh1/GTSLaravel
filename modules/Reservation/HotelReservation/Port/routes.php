<?php

use Custom\Framework\Support\Facades\Route;
use Module\Reservation\HotelReservation\Port\Controllers\InfoController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('searchActiveReservations', [InfoController::class, 'searchActiveReservations']);
Route::register('searchUpdatedReservations', [InfoController::class, 'searchUpdatedReservations']);
