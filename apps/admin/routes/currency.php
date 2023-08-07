<?php

declare(strict_types=1);

use App\Admin\Support\Facades\AclRoute;
use App\Admin\Http\Controllers;

AclRoute::for('currency')
    ->get('/get', Controllers\Data\CurrencyController::class . '@get', 'read', 'get');
