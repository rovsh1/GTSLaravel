<?php

use App\Api\Http\Controllers\TravelineController;
use App\Api\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class, 'api'])
    ->group(function () {
        Route::post('/', 'index')->name('index');
    });
