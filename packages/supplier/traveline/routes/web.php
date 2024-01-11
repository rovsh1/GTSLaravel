<?php

use Pkg\Supplier\Traveline\Http\Controllers\TravelineController;
use Pkg\Supplier\Traveline\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class, 'api'])
    ->group(function () {
        Route::post('/', 'index')->name('index');
        Route::get('/debug', 'debug')->name('debug');
    });
