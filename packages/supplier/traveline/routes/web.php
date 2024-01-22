<?php

use Illuminate\Support\Facades\Route;
use Pkg\Supplier\Traveline\Http\Controllers\TravelineController;
use Pkg\Supplier\Traveline\Http\Middleware\Authorize;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class, 'api'])
    ->group(function () {
        Route::post('/', 'index')->name('index');
    });
