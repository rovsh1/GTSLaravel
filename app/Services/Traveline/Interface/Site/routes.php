<?php

use GTS\Services\Traveline\Interface\Site\Http\Controllers\TravelineController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'integration',
    'as'     => 'integration.',
], function () {

    Route::group([
        'prefix' => 'traveline',
        'as'     => 'traveline.',
    ], function () {
        Route::post('/', [TravelineController::class, 'index'])->name('index');
    });

});
