<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('hotel', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-discount', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-price-list', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-service', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-usability', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-landmark', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-landmark-type', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-quota', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel-user', Controllers\Reference\CountryController::class);
