<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('administrator', Controllers\Administration\AdministratorController::class, ['except' => ['show']]);
AclRoute::resource('access-group', Controllers\Administration\AccessGroupController::class, ['except' => ['show']]);
AclRoute::resource('constant', Controllers\Reference\CountryController::class);
AclRoute::resource('mail-template', Controllers\Reference\CountryController::class);
AclRoute::resource('mail-log', Controllers\Reference\CountryController::class);

AclRoute::resource('country', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('city', Controllers\Reference\CityController::class, ['except' => ['show']]);
AclRoute::resource('currency', Controllers\Reference\CurrencyController::class, ['except' => ['show']]);

AclRoute::resource('airport', Controllers\Reference\CurrencyController::class, ['except' => ['show']]);
