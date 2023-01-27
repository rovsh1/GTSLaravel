<?php

use Illuminate\Support\Facades\Route;

use GTS\Administrator\UI\Admin\Http\Controllers\CurrencyController;

Route::prefix('reference')->group(function() {
    Route::controller(CurrencyController::class)->prefix('currency')->group(function () {
        Route::get('/', 'index');
    });
});
