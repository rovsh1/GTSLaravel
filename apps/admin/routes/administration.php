<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::resource('administrator', Controllers\Administration\AdministratorController::class, ['except' => ['show']]);
AclRoute::resource('access-group', Controllers\Administration\AccessGroupController::class, ['except' => ['show']]);

AclRoute::resource('constant', Controllers\Administration\ConstantController::class, ['except' => ['show']]);
AclRoute::resource('mail-template', Controllers\Data\CountryController::class);
AclRoute::resource('mail-log', Controllers\Data\CountryController::class);
