<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Site\Http\Controllers\MainController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::group([], __DIR__ . '/auth.php');
Route::group([], __DIR__ . '/profile.php');
