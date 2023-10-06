<?php

use App\Admin\Http\Controllers\Pricing\MarkupGroup;
use App\Admin\Http\Controllers\Pricing\MarkupGroupController;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('markup-group')
    ->get('/list', MarkupGroupController::class . '@list', 'read', 'list')
    ->resource('clients', MarkupGroup\ClientsController::class, [
        'except' => ['show']
    ])
    ->resource('rules', MarkupGroup\RulesController::class, [
        'except' => ['show']
    ]);
