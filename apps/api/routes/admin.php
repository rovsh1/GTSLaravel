<?php

use App\Api\Http\Admin\Controllers\V1\Reference\CityController;

Route::middleware('api')->prefix('v1')->group(callback: function () {

    Route::prefix('reference')->group(callback: function () {

        Route::controller(CityController::class)
            ->prefix('city')
            ->group(callback: function () {
                Route::get('/search', 'search');
            });
    });

});
