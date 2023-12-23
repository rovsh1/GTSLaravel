<?php

use App\Hotel\Http\Controllers\HotelController;
use App\Hotel\Http\Controllers\NotesController;
use App\Hotel\Http\Controllers\ServiceController;
use App\Hotel\Http\Controllers\UsabilityController;
use Illuminate\Support\Facades\Route;

Route::controller(HotelController::class)
    ->prefix('hotel')
    ->as('hotel.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get', 'get')->name('get');

        Route::get('/settings', 'settings')->name('settings');
        Route::put('/settings', 'updateSettings')->name('settings.update');

        Route::prefix('notes')
            ->as('notes.')
            ->group(function () {
                Route::get('/', [NotesController::class, 'edit'])->name('edit');
                Route::put('/', [NotesController::class, 'update'])->name('update');
            });

        Route::prefix('services')
            ->as('services.')
            ->group(function () {
                Route::get('/', [ServiceController::class, 'edit'])->name('edit');
                Route::put('/', [ServiceController::class, 'update'])->name('update');
            });

        Route::prefix('usabilities')
            ->as('usabilities.')
            ->group(function () {
                Route::get('/', [UsabilityController::class, 'edit'])->name('edit');
                Route::put('/', [UsabilityController::class, 'update'])->name('update');
            });
    });
