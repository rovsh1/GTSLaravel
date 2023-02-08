<?php

use Custom\Framework\Support\Facades\Route;

use GTS\Hotel\UI\Port\Controllers\InfoController;

Route::register('findById', [InfoController::class, 'findById']);
Route::register('getRoomsWithPriceRatesByHotelId', [InfoController::class, 'getRoomsWithPriceRatesByHotelId']);
