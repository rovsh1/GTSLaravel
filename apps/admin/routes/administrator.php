<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('administrator')
    ->get('/get', Controllers\Administration\AdministratorController::class . '@get', null, 'get');
