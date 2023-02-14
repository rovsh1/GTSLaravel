<?php

use Custom\Framework\Support\Facades\Route;

use GTS\Hotel\UI\Port\Controllers\InfoController;
use GTS\Hotel\UI\Port\Controllers\ReservationController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('getRoomsWithPriceRatesByHotelId', [InfoController::class, 'getRoomsWithPriceRatesByHotelId']);
Route::register('reserveQuota', [ReservationController::class, 'reserveQuota']);
