<?php

use App\Hotel\Http\Controllers\QuotaController;
use App\Hotel\Http\Controllers\Reference\CancelReasonsController;
use App\Hotel\Http\Controllers\Reference\CountriesController;
use App\Hotel\Http\Controllers\Reference\CurrenciesController;
use Illuminate\Support\Facades\Route;

Route::controller(QuotaController::class)
    ->prefix('currencies')
    ->as('currencies.')
    ->group(function () {
        Route::get('/', [CurrenciesController::class, 'list'])->name('list');
    });

Route::controller(QuotaController::class)
    ->prefix('countries')
    ->as('countries.')
    ->group(function () {
        Route::get('/', [CountriesController::class, 'list'])->name('list');
    });

Route::controller(QuotaController::class)
    ->prefix('cancel-reasons')
    ->as('cancel-reasons.')
    ->group(function () {
        Route::get('/', [CancelReasonsController::class, 'list'])->name('list');
    });
