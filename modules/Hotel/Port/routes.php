<?php

use Custom\Framework\Support\Facades\Route;
use Module\Hotel\Port\Controllers\PriceController;
use Module\Hotel\Port\Controllers\RoomQuotaController;
use Module\Hotel\Port\Controllers\AdditionalConditionsController;

//Route::register('updateRoomsPositions', [PriceController::class, 'updateRoomsPositions']);

Route::register('getAdditionalConditions', [AdditionalConditionsController::class, 'getAdditionalConditions']);

Route::register('getHotelQuotas', [RoomQuotaController::class, 'getHotelQuotas']);
Route::register('updateRoomQuota', [RoomQuotaController::class, 'updateRoomQuota']);
Route::register('openRoomQuota', [RoomQuotaController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [RoomQuotaController::class, 'closeRoomQuota']);
