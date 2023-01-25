<?php

use Illuminate\Support\Facades\Route;
use GTS\Services\Traveline\UI\Api\Http\Controllers\TravelineController;
use GTS\Services\Traveline\UI\Api\Http\Middleware\Authorize;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class])
    ->group(function () {
        Route::post('/', 'index')->name('index');
    });
