<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('report-order')
    ->post('/generate', Controllers\Report\OrderController::class . '@generate', 'create', 'generate');

