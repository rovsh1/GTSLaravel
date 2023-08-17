<?php

declare(strict_types=1);

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('client')
    ->resource('legals', Controllers\Client\LegalsController::class, [
        'except' => ['show']
    ])
    ->get('/legals/search', Controllers\Client\LegalsController::class . '@search', 'read', 'legals.search')
    ->get('/industry/list', Controllers\Client\IndustryController::class . '@list', 'read', 'industry.list')
    ->post('/create/dialog', Controllers\Client\ClientController::class . '@storeDialog', 'create', 'dialog.store');
