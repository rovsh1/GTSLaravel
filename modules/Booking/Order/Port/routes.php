<?php

use Module\Booking\Order\Port\Controllers\MainController;
use Sdk\Module\Support\Route;


Route::register('getActiveOrders', [MainController::class, 'getActiveOrders']);
Route::register('createOrder', [MainController::class, 'createOrder']);
Route::register('getOrder', [MainController::class, 'getOrder']);
