<?php

use App\Admin\Http\Controllers\Data\AirportController;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('airport')
    ->get('/search', AirportController::class . '@search', 'read', 'search');
