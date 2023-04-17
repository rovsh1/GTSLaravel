<?php

use Custom\Framework\Support\Facades\Route;
use Module\Services\MailManager\Port\Controllers\AdminController;
use Module\Services\MailManager\Port\Controllers\SendController;

Route::register('templates-list', AdminController::class);
Route::register('get-queue', AdminController::class);

Route::register('send', SendController::class);
Route::register('send-sync', SendController::class);
Route::register('send-template', SendController::class);
Route::register('send-template-sync', SendController::class);
