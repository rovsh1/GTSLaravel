<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('service-provider')
    ->resource('contacts', Controllers\ServiceProvider\ContactController::class, [
        'except' => ['show', 'index']
    ])
    ->resource('services', Controllers\ServiceProvider\ServicesController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ]);



