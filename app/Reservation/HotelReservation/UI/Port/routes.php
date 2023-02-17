<?php

use Custom\Framework\Support\Facades\Route;

use GTS\Reservation\HotelReservation\UI\Port\Controllers\InfoController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('searchActiveReservations', [InfoController::class, 'searchActiveReservations']);
Route::register('searchUpdatedReservations', [InfoController::class, 'searchUpdatedReservations']);
