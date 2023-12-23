<?php

use App\Hotel\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->as('profile.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::addRoute(['get', 'post'], '/settings', 'settings')->name('name');
        Route::addRoute(['get', 'post'], '/password', 'password')->name('password');
        Route::addRoute(['get', 'post'], '/photo', 'photo')->name('photo');
    });
