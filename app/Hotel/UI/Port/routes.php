<?php

use Custom\Framework\Support\Facades\Route;

use GTS\Hotel\UI\Port\Controllers\InfoController;
use GTS\Hotel\UI\Port\Controllers\ReservationController;
use GTS\Hotel\UI\Port\Controllers\RoomPriceController;
use GTS\Hotel\UI\Port\Controllers\RoomQuotaController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('getRoomsWithPriceRatesByHotelId', [InfoController::class, 'getRoomsWithPriceRatesByHotelId']);

Route::register('updateRoomQuota', [RoomQuotaController::class, 'updateRoomQuota']);
Route::register('openRoomQuota', [RoomQuotaController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [RoomQuotaController::class, 'closeRoomQuota']);

Route::register('updateRoomPrice', [RoomPriceController::class, 'updateRoomPrice']);

Route::register('getActiveReservations', [ReservationController::class, 'getActiveReservations']);
