<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('currency')
    ->get('/update-rates', Controllers\Data\CurrencyController::class . '@updateRates', 'update', 'update-rates');
