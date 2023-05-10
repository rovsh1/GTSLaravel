<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('client')
    ->get(
        '/currencies',
        Controllers\Client\ClientController::class . '@searchCurrencies',
        'read',
        'currencies'
    );
