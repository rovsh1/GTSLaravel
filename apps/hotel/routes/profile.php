<?php

use App\Hotel\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->as('profile.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::match(['get', 'post'], '/settings', 'settings')->name('name');
        Route::match(['get', 'post'], '/password', 'password')->name('password');
        Route::match(['get', 'post'], '/photo', 'photo')->name('photo');
    });
