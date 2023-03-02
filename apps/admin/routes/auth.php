<?php

use Illuminate\Support\Facades\Route;

use App\Admin\Http\Controllers\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::withoutMiddleware('auth:admin')->get('/login', 'index')->name('auth.login');
    Route::withoutMiddleware('auth:admin')->post('/login', 'login')->name('auth.submit');

    Route::get('/logout', 'logout')->name('auth.logout');
});
