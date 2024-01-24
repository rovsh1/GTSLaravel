<?php

use App\Admin\Http\Controllers\AuthController;
use App\Admin\Http\Controllers\ProfileController;
use App\Admin\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::withoutMiddleware(Authenticate::class)->get('/login', 'index')->name('auth.login');
    Route::withoutMiddleware(Authenticate::class)->post('/login', 'login')->name('auth.submit');

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
        Route::delete( '/delete', 'delete')->name('.delete');
    });
