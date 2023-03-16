<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('client', Controllers\Reference\CountryController::class);
AclRoute::resource('client-user', Controllers\Reference\CountryController::class);
