<?php

use Illuminate\Support\Facades\Route;
use App\Api\Http\Controllers\TravelineController;
use App\Api\Http\Middleware\Authorize;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class])
    ->group(function () {
        Route::post('/', 'index')->name('index');
    });
