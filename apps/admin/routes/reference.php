<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('country', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('city', Controllers\Reference\CityController::class, ['except' => ['show']]);
AclRoute::resource('currency', Controllers\Reference\CurrencyController::class, ['except' => ['show']]);

AclRoute::resource('airport', Controllers\Reference\CurrencyController::class, ['except' => ['show']]);
//AclRoute::assignRoute('reference.country.auth', 'country.update');

//dd(app('router'));
