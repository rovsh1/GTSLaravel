<?php

declare(strict_types=1);

use App\Admin\Support\Facades\AclRoute;
use App\Admin\Http\Controllers;

AclRoute::for('client')
    ->get('/search', Controllers\Client\ClientController::class . '@search', 'read', 'search')
    ->resource('legals', Controllers\Client\LegalsController::class, [
        'except' => ['show']
    ])
    ->get('/legals/search', Controllers\Client\LegalsController::class . '@search', 'read', 'search');
