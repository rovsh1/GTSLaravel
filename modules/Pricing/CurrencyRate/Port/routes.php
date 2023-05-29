<?php

use Module\Pricing\CurrencyRate\Port\Controllers\MainController;
use Sdk\Module\Support\Route;

Route::register('rate', MainController::class);
Route::register('update', MainController::class);
Route::register('update-country', MainController::class);
