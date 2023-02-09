<?php

use Illuminate\Support\Facades\Route;

use GTS\Administrator\UI\Admin\Http\Controllers;

Route::controller(Controllers\AuthController::class)->group(function () {
    Route::withoutMiddleware('auth:admin')->get('/login', 'index')->name('auth.login');
    Route::withoutMiddleware('auth:admin')->post('/login', 'login')->name('auth.submit');

    Route::get('/logout', 'logout')->name('auth.logout');
});

//Route::resource('currency', Controllers\CurrencyController::class);

Route::prefix('reference')->group(function () {
    // Todo можно так упростить создание базовых роутов. Однако может быть зарегистрирован лишний
    Route::resource('currency', Controllers\CurrencyController::class);

//        ->group(function () {
//        Route::get('/', 'index')->name('index');
//        Route::get('/create', 'create')->name('create');
//    });

    Route::controller(Controllers\CountryController::class)->prefix('country')->name('country.')->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

Route::controller(Controllers\TestController::class)->prefix('test')->group(function () {
    Route::match(['get', 'post'], '/form', 'form');
});
