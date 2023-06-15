<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('service-provider')
    ->resource('contacts', Controllers\ServiceProvider\ContactController::class, [
        'except' => ['show', 'index']
    ])
    ->resource('seasons', Controllers\ServiceProvider\SeasonController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->resource('services', Controllers\ServiceProvider\ServicesController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->get('/services/search', Controllers\ServiceProvider\ServicesController::class . '@search', 'read', 'services.search');



