<?php

use App\Site\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->as('profile.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });
