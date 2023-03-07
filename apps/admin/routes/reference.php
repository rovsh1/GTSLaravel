<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('reference.country', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('reference.city', Controllers\Reference\CityController::class, ['except' => ['show']]);
AclRoute::resource('reference.currency', Controllers\Reference\CurrencyController::class, ['except' => ['show']]);
//AclRoute::assignRoute('reference.country.auth', 'country.update');

//dd(app('router'));
