<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('report-tourist', Controllers\Reference\CountryController::class);
