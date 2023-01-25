<?php

use GTS\Services\Traveline\Interface\Api\Http\Controllers\TravelineController;
use GTS\Services\Traveline\Interface\Api\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'integration',
    'as' => 'integration.',
], function () {

    Route::group([
        'prefix' => 'traveline',
        'as' => 'traveline.',
        'middleware' => [Authorize::class]
    ], function () {
        Route::post('/', [TravelineController::class, 'index'])
            ->name('index');
    });

});
