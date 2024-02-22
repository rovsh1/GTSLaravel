<?php

use App\Hotel\Http\Controllers\BookingController;
use App\Hotel\Http\Controllers\BookingRequestController;
use Illuminate\Support\Facades\Route;

Route::controller(BookingController::class)
    ->prefix('booking')
    ->as('booking.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{booking}', 'show')->name('show');
        Route::get('/{booking}/timeline', 'timeline')->name('timeline');

        Route::get('/{booking}/get', 'get')->name('get');
        Route::put('/{booking}/note', 'updateNote')->name('note.update');
        Route::get('/{booking}/order/guests', 'getOrderGuests')->name('order.guests');

        Route::get('/status/list', 'getStatuses')->name('status.list');
        Route::put('/{booking}/status/update', 'updateStatus')->name('status.update');
        Route::get('/{booking}/status/history', 'getStatusHistory')->name('status.history');
        Route::post('/{booking}/status/no-checkin', 'setNoCheckIn')->name('status.no-checkin');

        Route::get('/{booking}/actions/available', 'getAvailableActions')->name('actions.available.get');

        Route::put('/{booking}/external/number', 'updateExternalNumber')->name('external.number.update');

        Route::put('/{booking}/price/penalty', 'updatePenalty')->name('price.penalty.update');

        Route::controller(BookingRequestController::class)
            ->prefix('/{booking}/request')
            ->as('request.')
            ->group(function () {
                Route::get('/list', 'getBookingRequests')->name('list');
                Route::get('/{request}/file', 'getFileInfo')->name('download');
            });
    });
