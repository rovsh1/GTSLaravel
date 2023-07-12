<?php

use Custom\Framework\Support\Facades\Route;
use Module\Hotel\Port\Controllers\InfoController;
use Module\Hotel\Port\Controllers\RoomPriceController;
use Module\Hotel\Port\Controllers\RoomQuotaController;


Route::register('findById', [InfoController::class, 'findById']);
Route::register('getRoomsWithPriceRatesByHotelId', [InfoController::class, 'getRoomsWithPriceRatesByHotelId']);

Route::register('updateRoomQuota', [RoomQuotaController::class, 'updateRoomQuota']);
Route::register('updateReleaseDays', [RoomQuotaController::class, 'updateReleaseDays']);
Route::register('openRoomQuota', [RoomQuotaController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [RoomQuotaController::class, 'closeRoomQuota']);

Route::register('updateRoomPrice', [RoomPriceController::class, 'updateRoomPrice']);
