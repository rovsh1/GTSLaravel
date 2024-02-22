<?php

declare(strict_types=1);

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('cancel-reason')
    ->get('/list', Controllers\Data\CancelReasonController::class . '@list', 'read', 'list');
