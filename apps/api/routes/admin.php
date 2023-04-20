<?php

use App\Api\Http\Admin\Controllers\V1\Reference\CityController;
use App\Api\Http\Admin\Controllers\V1\Hotel\ImageController;

Route::middleware('api')->prefix('v1')->group(callback: function () {

    Route::prefix('reference')->group(callback: function () {

        Route::controller(CityController::class)
            ->prefix('city')
            ->group(callback: function () {
                Route::get('/search', 'search');
            });
    });

    Route::prefix('hotel/{hotel}')->group(callback: function () {
        Route::controller(ImageController::class)
            ->prefix('images')
            ->group(callback: function () {
                Route::get('/', 'get');
            });
    });

});
