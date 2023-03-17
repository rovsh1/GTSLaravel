<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('invoice', Controllers\Data\CountryController::class);
