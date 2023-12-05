<?php

use App\Hotel\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::group([], __DIR__ . '/auth.php');
