<?php

use App\Api\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::controller(TestController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });
