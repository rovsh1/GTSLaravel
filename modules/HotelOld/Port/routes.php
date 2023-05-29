<?php

use Module\HotelOld\Port\Controllers\InfoController;
use Module\HotelOld\Port\Controllers\RoomPriceController;
use Module\HotelOld\Port\Controllers\RoomQuotaController;
use Sdk\Module\Support\Route;


Route::register('findById', [InfoController::class, 'findById']);
Route::register('getRoomsWithPriceRatesByHotelId', [InfoController::class, 'getRoomsWithPriceRatesByHotelId']);

Route::register('updateRoomQuota', [RoomQuotaController::class, 'updateRoomQuota']);
Route::register('openRoomQuota', [RoomQuotaController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [RoomQuotaController::class, 'closeRoomQuota']);

Route::register('updateRoomPrice', [RoomPriceController::class, 'updateRoomPrice']);
