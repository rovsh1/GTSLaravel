<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('site-page', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('site-faq', Controllers\Data\CountryController::class, ['except' => ['show']]);
