<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('country', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('city', Controllers\Data\CityController::class, ['except' => ['show']]);
AclRoute::resource('currency', Controllers\Data\CurrencyController::class, ['except' => ['show']]);

AclRoute::resource('airport', Controllers\Data\AirportController::class, ['except' => ['show']]);

AclRoute::resource('landmark', Controllers\Data\LandmarkController::class, ['except' => ['show']]);
AclRoute::resource('landmark-type', Controllers\Data\LandmarkTypeController::class, ['except' => ['show']]);