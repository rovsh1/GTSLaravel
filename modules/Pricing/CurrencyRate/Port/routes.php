<?php

use Custom\Framework\Support\Facades\Route;
use Module\Pricing\CurrencyRate\Port\Controllers\MainController;

Route::register('rate', MainController::class);
Route::register('update', MainController::class);
Route::register('update-country', MainController::class);
