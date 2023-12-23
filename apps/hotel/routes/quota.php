<?php

use App\Hotel\Http\Controllers\QuotaController;
use Illuminate\Support\Facades\Route;

Route::controller(QuotaController::class)
    ->prefix('quotas')
    ->as('quotas.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });
