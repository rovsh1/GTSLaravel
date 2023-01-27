<?php

use Illuminate\Support\Facades\Route;

use GTS\Administrator\UI\Admin\Http\Controllers;

Route::prefix('reference')->group(function () {
    Route::controller(Controllers\CurrencyController::class)->prefix('currency')->group(function () {
        Route::get('/', 'index');
    });

    Route::controller(Controllers\CountryController::class)->prefix('country')->group(function () {
        Route::get('/', 'index');
    });
});
