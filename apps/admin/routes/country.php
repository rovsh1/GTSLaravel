<?php

declare(strict_types=1);

use App\Admin\Support\Facades\AclRoute;
use App\Admin\Http\Controllers;

AclRoute::for('country')
    ->get('/search', Controllers\Data\CountryController::class . '@search', 'read', 'search');
