<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('client', Controllers\Data\CountryController::class);
AclRoute::resource('client-user', Controllers\Data\CountryController::class);
