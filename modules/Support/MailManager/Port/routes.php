<?php

use Module\Support\MailManager\Port\Controllers\AdminController;
use Module\Support\MailManager\Port\Controllers\SendController;
use Sdk\Module\Support\Route;

Route::register('templates-list', AdminController::class);
Route::register('get-queue', AdminController::class);

Route::register('send', SendController::class);
Route::register('send-sync', SendController::class);
Route::register('send-template', SendController::class);
Route::register('send-template-sync', SendController::class);
