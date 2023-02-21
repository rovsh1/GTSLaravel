<?php

use Illuminate\Support\Facades\Route;
use App\Api\Http\Controllers\TravelineController;
use App\Api\Http\Middleware\Authorize;

//$this->moduleUIRoutes([
//    'prefix' => 'traveline',
//    'as' => 'traveline.',
//    'middleware' => ['api']
//], 'Traveline', 'Api');

Route::controller(TravelineController::class)
    ->middleware([Authorize::class])
    ->group(function () {
        Route::post('/', 'index')->name('index');
    });
