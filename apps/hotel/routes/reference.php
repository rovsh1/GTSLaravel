<?php

use App\Hotel\Http\Controllers\QuotaController;
use App\Hotel\Http\Controllers\Reference\CurrenciesController;
use Illuminate\Support\Facades\Route;

Route::controller(QuotaController::class)
    ->prefix('currencies')
    ->as('currencies.')
    ->group(function () {
        Route::get('/', [CurrenciesController::class, 'list'])->name('list');
    });
