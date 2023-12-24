<?php

use App\Hotel\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::controller(ContractController::class)
    ->prefix('contracts')
    ->as('contracts.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{contract}', 'get')->name('get');
    });
