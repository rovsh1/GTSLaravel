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
        Route::get('/recovery', 'recovery')->name('recovery');
        Route::post('/recovery', 'processRecovery')->name('recovery.submit');
        Route::get('/partner', 'partner')->name('partner');
        Route::post('/partner', 'processPartner')->name('partner.submit');
    });

Route::controller(ProfileController::class)
    ->prefix('profile.')
    ->name('profile')
    ->group(function () {
        Route::get('/logout', 'logout')->name('logout');
//        Route::get('/', 'index');
//        Route::match(['get', 'post'], '/settings', 'settings')->name('.name');
//        Route::match(['get', 'post'], '/password', 'password')->name('.password');
//        Route::match(['get', 'post'], '/photo', 'photo')->name('.photo');
    });
