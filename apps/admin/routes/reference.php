<?php

use Illuminate\Support\Facades\Route;

use App\Admin\Http\Controllers;

Route::prefix('reference')->group(function () {
    // Todo можно так упростить создание базовых роутов. Однако может быть зарегистрирован лишний
    Route::resource('currency', Controllers\CurrencyController::class, ['except' => ['show']]);
    Route::resource('country', Controllers\CountryController::class);
    Route::resource('city', Controllers\CityController::class);
});
