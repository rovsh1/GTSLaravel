<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('compound-reservation', Controllers\Data\CountryController::class);
AclRoute::resource('hotel-reservation', Controllers\Reservation\HotelReservationController::class);
AclRoute::resource('airport-reservation', Controllers\Data\CountryController::class);
AclRoute::resource('transfer-reservation', Controllers\Data\CountryController::class);
AclRoute::resource('additional-reservation', Controllers\Data\CountryController::class);
AclRoute::resource('airport-service', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('transfer-service', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('service-provider', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('transport-type', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('transport', Controllers\Data\CountryController::class, ['except' => ['show']]);
//AclRoute::assignRoute('reference.country.auth', 'country.update');

//dd(app('router'));
