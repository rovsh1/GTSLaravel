<?php

declare(strict_types=1);

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('client')
    ->resource('legals', Controllers\Client\LegalsController::class, [
        'except' => ['show']
    ])
    ->resource('users', Controllers\Client\ClientUserController::class, [
        'except' => ['show', 'edit', 'update', 'destroy']
    ])
    ->get('/users/search',Controllers\Client\ClientUserController::class . '@search', 'read', 'users.search')
    ->delete(
        '/{client}/users/bulk',
        Controllers\Client\ClientUserController::class . '@bulkDelete',
        'delete',
        'delete.bulk'
    )
    ->resource('documents', Controllers\Client\DocumentController::class, [
        'except' => ['show']
    ])
    ->get('/legals/search', Controllers\Client\LegalsController::class . '@search', 'read', 'legals.search')
    ->get('/industry/list', Controllers\Client\IndustryController::class . '@list', 'read', 'industry.list')
    ->get('/list', Controllers\Client\ClientController::class . '@list', 'read', 'list')
    ->post('/create/dialog', Controllers\Client\ClientController::class . '@storeDialog', 'create', 'dialog.store')
    ->resource('contacts', Controllers\Client\ContactController::class, ['except' => ['show', 'index']]);
