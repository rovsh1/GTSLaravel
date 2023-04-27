<?php

use Custom\Framework\Support\Facades\Route;
use Module\Hotel\Port\Controllers\PriceController;
use Module\Hotel\Port\Controllers\RoomQuotaController;

//Route::register('updateRoomsPositions', [PriceController::class, 'updateRoomsPositions']);

Route::register('getRoomQuota', [RoomQuotaController::class, 'getRoomQuota']);
Route::register('updateRoomQuota', [RoomQuotaController::class, 'updateRoomQuota']);
Route::register('openRoomQuota', [RoomQuotaController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [RoomQuotaController::class, 'closeRoomQuota']);
