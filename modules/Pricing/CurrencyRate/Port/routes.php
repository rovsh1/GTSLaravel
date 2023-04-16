<?php

use Custom\Framework\Support\Facades\Route;
use Module\Pricing\CurrencyRate\Port\Controllers\MainController;

Route::register('get', MainController::class);
Route::register('all', MainController::class);
