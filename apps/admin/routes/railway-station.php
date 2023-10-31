<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('railway-station')
    ->get('/search', Controllers\Data\RailwayStationController::class . '@search', 'read', 'search');
