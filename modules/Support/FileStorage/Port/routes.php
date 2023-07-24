<?php

use Module\Support\FileStorage\Port\Controllers\ReadController;
use Module\Support\FileStorage\Port\Controllers\WriteController;
use Sdk\Module\Support\Route;

Route::register('create', WriteController::class);
Route::register('put', WriteController::class);
Route::register('delete', WriteController::class);

Route::register('find', ReadController::class);
Route::register('getEntityFile', ReadController::class);
Route::register('getEntityFiles', ReadController::class);
Route::register('getContents', [ReadController::class, 'getContents']);
Route::register('fileInfo', ReadController::class);
Route::register('url', ReadController::class);
