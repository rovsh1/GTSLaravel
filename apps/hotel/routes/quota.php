<?php

use App\Hotel\Http\Controllers\QuotaController;
use Illuminate\Support\Facades\Route;

Route::controller(QuotaController::class)
    ->prefix('quotas')
    ->as('quotas.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::post('/', 'get')->name('get');
        Route::put('/date/batch', 'batchUpdateDateQuota')->name('date.update.batch');
        Route::put('/rooms/{room}/quota', 'update')->name('update');
        Route::put('/rooms/{room}/quota/open', 'openQuota')->name('open');
        Route::put('/rooms/{room}/quota/close', 'closeQuota')->name('close');
        Route::put('/rooms/{room}/quota/reset', 'resetQuota')->name('reset');
    });
