<?php

use App\Site\Http\Controllers\AuthController;
use App\Site\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->as('auth.')
    ->middleware('throttle:6,1')//Не более 6 запросов в минуту
    ->group(function () {
        Route::withoutMiddleware(Authenticate::class)->get('/login', 'index')->name('login');
        Route::withoutMiddleware(Authenticate::class)->post('/login', 'login')->name('submit');
        Route::withoutMiddleware(Authenticate::class)->get('/register', 'registerPage')->name('register');
        Route::withoutMiddleware(Authenticate::class)->post('/register', 'register')->name('register.submit');
        Route::withoutMiddleware(Authenticate::class)->get('/forgot-password', 'forgotPasswordPage')->name('forgot-password');
        Route::withoutMiddleware(Authenticate::class)->get('/forgot-password/success', 'forgotPasswordSuccessPage')->name('forgot-password.success');
        Route::withoutMiddleware(Authenticate::class)->post('/forgot-password', 'forgotPassword')->name('forgot-password.submit');

        Route::withoutMiddleware(Authenticate::class)->get('/reset-password/{hash}', 'resetPasswordPage')->name('reset-password');
        Route::withoutMiddleware(Authenticate::class)->post('/reset-password/{hash}', 'resetPassword')->name('reset-password.submit');

        Route::get('/logout', 'logout')->name('logout');
    });
