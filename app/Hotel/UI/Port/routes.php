<?php

use Custom\Framework\Support\Facades\Route;

use GTS\Hotel\UI\Port\Controllers\InfoController;
use GTS\Hotel\UI\Port\Controllers\ReservationController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('getRoomsWithPriceRatesByHotelId', [InfoController::class, 'getRoomsWithPriceRatesByHotelId']);

Route::register('updateRoomQuota', [ReservationController::class, 'updateRoomQuota']);
Route::register('openRoomQuota', [ReservationController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [ReservationController::class, 'closeRoomQuota']);
Route::register('updateRoomRatePrice', [ReservationController::class, 'updateRoomRatePrice']);
