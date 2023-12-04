<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Hotel\Http\Controllers\DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::group([], __DIR__ . '/auth.php');
