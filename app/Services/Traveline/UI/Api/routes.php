<?php

use GTS\Services\Integration\Traveline\UI\Api\Http\Controllers\TravelineController;
use GTS\Services\Integration\Traveline\UI\Api\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class])
    ->group(function () {
        Route::post('/', 'index')->name('index');
    });
