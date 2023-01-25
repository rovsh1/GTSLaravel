<?php

use Illuminate\Support\Facades\Route;
use GTS\Services\Traveline\UI\Api\Http\Controllers\TravelineController;
use GTS\Services\Traveline\UI\Api\Http\Middleware\Authorize;


Route::group([
    'prefix' => 'traveline',
    'as' => 'traveline.',
    'middleware' => [Authorize::class]
], function () {

    Route::post('/', [TravelineController::class, 'index'])->name('index');

});
