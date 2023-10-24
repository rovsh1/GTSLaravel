<?php

use App\Api\Http\Traveline\Controllers\TravelineController;
use App\Api\Http\Traveline\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

Route::controller(TravelineController::class)
    ->middleware([Authorize::class, 'api'])
    ->group(function () {
        Route::post('/', 'index')->name('index');
        Route::get('/debug', 'debug')->name('debug');
    });
