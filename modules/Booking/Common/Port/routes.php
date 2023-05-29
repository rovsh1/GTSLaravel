<?php

use Module\Booking\Common\Port\Controllers\StatusController;
use Sdk\Module\Support\Route;

Route::register('getStatuses', [StatusController::class, 'getStatuses']);
Route::register('getAvailableStatuses', [StatusController::class, 'getAvailableStatuses']);
