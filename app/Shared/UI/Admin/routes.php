<?php

use Illuminate\Support\Facades\Route;

use GTS\Shared\UI\Admin\Http\Controllers;


Route::controller(Controllers\AuthController::class)->group(function () {
    Route::withoutMiddleware(['auth:admin'])->get('/login', fn() => view('auth.login'))->name('login.form');
    Route::withoutMiddleware(['auth:admin'])->post('/login', 'login')->name('login');

    Route::get('/logout', 'logout')->name('logout');
});
