<?php

use Custom\Framework\Support\Facades\Route;
use Module\Hotel\Port\Controllers\PriceController;
use Module\Hotel\Port\Controllers\RoomQuotaController;
use Module\Hotel\Port\Controllers\MarkupSettingsController;

//Route::register('updateRoomsPositions', [PriceController::class, 'updateRoomsPositions']);

Route::register('getHotelMarkupSettings', [MarkupSettingsController::class, 'getHotelMarkupSettings']);
Route::register('updateMarkupSettings', [MarkupSettingsController::class, 'updateMarkupSettings']);
Route::register('addMarkupSettingsCondition', [MarkupSettingsController::class, 'addMarkupSettingsCondition']);
Route::register('deleteMarkupSettingsCondition', [MarkupSettingsController::class, 'deleteMarkupSettingsCondition']);

Route::register('getHotelQuotas', [RoomQuotaController::class, 'getHotelQuotas']);
Route::register('updateRoomQuota', [RoomQuotaController::class, 'updateRoomQuota']);
Route::register('openRoomQuota', [RoomQuotaController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [RoomQuotaController::class, 'closeRoomQuota']);
Route::register('resetRoomQuota', [RoomQuotaController::class, 'resetRoomQuota']);
