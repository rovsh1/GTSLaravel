<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;
use Illuminate\Support\Facades\Route;

AclRoute::resource('reference.country', Controllers\CountryController::class, ['except' => ['show']]);
//Route::register('country', [
//    'index' => 'read',
//    'show' => 'read',
//    'create' => 'create',
//    'store' => 'create',
//    'edit' => 'update',
//    'update' => 'update',
//    'destroy' => 'delete',
//]);

//AclRoute::assignRoute('reference.country.auth', 'country.update');

Route::prefix('reference')->group(function () {
    // Todo можно так упростить создание базовых роутов. Однако может быть зарегистрирован лишний
    Route::resource('currency', Controllers\CurrencyController::class, ['except' => ['show']]);
    //Route::resource('country', Controllers\CountryController::class);
    Route::resource('city', Controllers\CityController::class);
});

//dd(app('router'));
