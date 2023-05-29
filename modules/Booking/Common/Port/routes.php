<?php

use Custom\Framework\Support\Facades\Route;
use Module\Booking\Common\Port\Controllers\StatusController;

Route::register('getStatuses', [StatusController::class, 'getStatuses']);
Route::register('getAvailableStatuses', [StatusController::class, 'getAvailableStatuses']);
Route::register('updateStatus', [StatusController::class, 'updateStatus']);
