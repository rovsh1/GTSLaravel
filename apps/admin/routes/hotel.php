<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('hotel', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel.price-list', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel.service', Controllers\Reference\CountryController::class);
AclRoute::resource('hotel.usability', Controllers\Reference\CountryController::class);
