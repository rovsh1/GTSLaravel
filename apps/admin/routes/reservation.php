<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('reservation', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-reservation', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-service', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('airport-service', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('transfer-service', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('service-provider', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('transport-type', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('transport', Controllers\Reference\CountryController::class, ['except' => ['show']]);
//AclRoute::assignRoute('reference.country.auth', 'country.update');

//dd(app('router'));
