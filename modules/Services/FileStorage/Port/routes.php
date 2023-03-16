<?php

use Custom\Framework\Support\Facades\Route;
use Module\Services\FileStorage\Port\Controllers\ReadController;
use Module\Services\FileStorage\Port\Controllers\WriteController;

Route::register('create', WriteController::class);
Route::register('put', WriteController::class);
Route::register('delete', WriteController::class);

Route::register('find', ReadController::class);
Route::register('getEntityFile', ReadController::class);
Route::register('getEntityFiles', ReadController::class);
Route::register('contents', [ReadController::class, 'getContents']);
Route::register('fileInfo', ReadController::class);
Route::register('url', ReadController::class);
