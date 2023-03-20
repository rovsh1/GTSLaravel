<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Admin\Http\Controllers\DashboardController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::group([], __DIR__ . '/auth.php');
Route::group([], __DIR__ . '/hotel.php');
Route::group([], __DIR__ . '/file-manager.php');
