<?php

use Illuminate\Support\Facades\Route;

use GTS\Shared\UI\Admin\Http\Controllers;


Route::controller(Controllers\AuthController::class)
    ->group(function () {
        Route::withoutMiddleware(['auth:admin'])
            ->get('/login', 'login')
            ->name('login');
    });
