<?php

use App\Hotel\Http\Controllers\AuthController;
use App\Hotel\Http\Controllers\ProfileController;
use App\Hotel\Http\Middleware\Authenticate;
use App\Hotel\Http\Middleware\TryAuthenticate;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('auth')
    ->as('auth.')
    ->middleware([TryAuthenticate::class, 'guest:hotel'])
    ->withoutMiddleware(Authenticate::class)
    ->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'processLogin')->name('login.submit');
        Route::get('/logout', 'logout')->name('logout');
        Route::get('/recovery', 'recovery')->name('recovery');
        Route::post('/recovery', 'processRecovery')->name('recovery.submit');
        Route::get('/partner', 'partner')->name('partner');
        Route::post('/partner', 'processPartner')->name('partner.submit');
    });
