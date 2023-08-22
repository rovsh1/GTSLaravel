<?php

declare(strict_types=1);

use App\Admin\Support\Facades\AclRoute;
use App\Admin\Http\Controllers;

AclRoute::for('city')
    ->get('/search', Controllers\Data\CityController::class . '@search', 'read', 'search');
