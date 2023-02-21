<?php

use Illuminate\Support\Facades\Route;

use App\Admin\Http\Controllers;

Route::get('/', fn() => redirect(route('country.index')))->name('home');

Route::controller(Controllers\AuthController::class)->group(function () {
    Route::withoutMiddleware('auth:admin')->get('/login', 'index')->name('auth.login');
    Route::withoutMiddleware('auth:admin')->post('/login', 'login')->name('auth.submit');

    Route::get('/logout', 'logout')->name('auth.logout');
});

//Route::resource('currency', Controllers\CurrencyController::class);

Route::prefix('reference')->group(function () {
    // Todo можно так упростить создание базовых роутов. Однако может быть зарегистрирован лишний
    Route::resource('currency', Controllers\CurrencyController::class, ['except' => ['show']]);
    Route::resource('country', Controllers\CountryController::class);
    Route::resource('city', Controllers\CityController::class);
});

Route::controller(Controllers\TestController::class)->prefix('test')->group(function () {
    Route::match(['get', 'post'], '/form', 'form');
});
