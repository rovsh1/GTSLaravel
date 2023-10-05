<?php

use App\Admin\Http\Controllers\Pricing\MarkupGroup;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('markup-group')
    ->resource('clients', MarkupGroup\ClientsController::class, [
        'except' => ['show']
    ])
    ->resource('rules', MarkupGroup\RulesController::class, [
        'except' => ['show']
    ]);
