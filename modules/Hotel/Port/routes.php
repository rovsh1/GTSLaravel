<?php

use Custom\Framework\Support\Facades\Route;
use Module\Hotel\Port\Controllers\PriceController;
use Module\Hotel\Port\Controllers\RoomQuotaController;
use Module\Hotel\Port\Controllers\MarkupSettingsController;

//Route::register('updateRoomsPositions', [PriceController::class, 'updateRoomsPositions']);

//наценки
Route::register('getHotelMarkupSettings', [MarkupSettingsController::class, 'getHotelMarkupSettings']);
Route::register('updateMarkupSettingsValue', [MarkupSettingsController::class, 'updateHotelMarkupSettingsValue']);
Route::register('getRoomMarkupSettings', [MarkupSettingsController::class, 'getRoomMarkupSettings']);
Route::register('updateRoomMarkupSettings', [MarkupSettingsController::class, 'updateRoomMarkupSettings']);

//квоты
Route::register('getQuotas', [RoomQuotaController::class, 'getQuotas']);
Route::register('getAvailableQuotas', [RoomQuotaController::class, 'getAvailableQuotas']);
Route::register('getSoldQuotas', [RoomQuotaController::class, 'getSoldQuotas']);
Route::register('getStoppedQuotas', [RoomQuotaController::class, 'getStoppedQuotas']);
Route::register('updateRoomQuota', [RoomQuotaController::class, 'updateRoomQuota']);
Route::register('openRoomQuota', [RoomQuotaController::class, 'openRoomQuota']);
Route::register('closeRoomQuota', [RoomQuotaController::class, 'closeRoomQuota']);
Route::register('resetRoomQuota', [RoomQuotaController::class, 'resetRoomQuota']);

//цены
Route::register('getSeasonsPrices', [PriceController::class, 'getSeasonsPrices']);
Route::register('setSeasonPrice', [PriceController::class, 'setSeasonPrice']);
Route::register('getDatePrices', [PriceController::class, 'getDatePrices']);
Route::register('setDatePrice', [PriceController::class, 'setDatePrice']);
