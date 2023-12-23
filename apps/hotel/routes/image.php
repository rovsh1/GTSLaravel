<?php

use App\Hotel\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::controller(ImageController::class)
    ->prefix('images')
    ->as('images.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
//        Route::get('/{booking}', 'show')->name('show');
    });
