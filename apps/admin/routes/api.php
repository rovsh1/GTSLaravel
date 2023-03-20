<?php

use App\Admin\Http\Controllers\Api\V1;

Route::prefix('v1')->group(callback: function () {

    Route::prefix('reference')->group(callback: function () {

        Route::controller(V1\Reference\CityController::class)
            ->prefix('city')
            ->group(callback: function () {
                Route::get('/search', 'search');
            });
    });

});
