<?php

namespace Gsdk\Filemanager\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Admin\Http\Controllers\FilemanagerController;

Route::controller(FilemanagerController::class)
    ->prefix('filemanager')
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/files', 'files');
        Route::post('/upload', 'upload');
        Route::post('/move', 'move');
        Route::post('/folder', 'folder');
        Route::post('/rename', 'rename');
        Route::post('/delete', 'delete');
    });
