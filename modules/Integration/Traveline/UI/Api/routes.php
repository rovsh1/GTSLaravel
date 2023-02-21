<?php

use Illuminate\Support\Facades\Route;
use Module\Integration\Traveline\UI\Api\Http\Controllers\TravelineController;
use Module\Integration\Traveline\UI\Api\Http\Middleware\Authorize;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class])
    ->group(function () {
        Route::post('/', 'index')->name('index');
    });
