<?php

use Custom\Framework\Support\Facades\Route;
use Module\Booking\Order\Port\Controllers\MainController;


Route::register('getActiveOrders', [MainController::class, 'getActiveOrders']);
Route::register('createOrder', [MainController::class, 'createOrder']);
Route::register('getOrder', [MainController::class, 'getOrder']);
