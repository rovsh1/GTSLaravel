<?php

use Illuminate\Support\Facades\Route;

use App\Admin\Http\Controllers\AccountController;
use App\Admin\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::withoutMiddleware('auth:admin')->get('/login', 'index')->name('auth.login');
    Route::withoutMiddleware('auth:admin')->post('/login', 'login')->name('auth.submit');

    Route::get('/logout', 'logout')->name('auth.logout');
});

Route::controller(AccountController::class)
    ->prefix('settings')
    ->name('settings')
    ->group(function () {
        Route::get('/', 'settings');//->name('');
        Route::match(['get', 'post'], '/name', 'name')->name('.name');
        Route::match(['get', 'post'], '/birthday', 'birthday')->name('.birthday');
        Route::match(['get', 'post'], '/gender', 'gender')->name('.gender');
        Route::match(['get', 'post'], '/password', 'password')->name('.password');
        Route::match(['get', 'post'], '/photo', 'photo')->name('.photo');
    });
