<?php

use App\Admin\Http\Controllers\AuthController;
use App\Admin\Http\Controllers\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::withoutMiddleware('auth:admin')->get('/login', 'index')->name('auth.login');
    Route::withoutMiddleware('auth:admin')->post('/login', 'login')->name('auth.submit');

    Route::get('/logout', 'logout')->name('auth.logout');
});

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->name('profile')
    ->group(function () {
        Route::get('/', 'index');
        Route::match(['get', 'post'], '/settings', 'settings')->name('.name');
        Route::match(['get', 'post'], '/password', 'password')->name('.password');
        Route::match(['get', 'post'], '/photo', 'photo')->name('.photo');
    });
