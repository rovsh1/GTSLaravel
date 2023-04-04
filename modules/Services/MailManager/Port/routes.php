<?php

use Custom\Framework\Support\Facades\Route;
use Module\Services\MailManager\Port\Controllers\AdminController;

Route::register('list', AdminController::class);
