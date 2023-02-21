<?php

use App\Site\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::controller(FileController::class)
    ->group(function () {
        Route::get('file/{guid}', 'file');
    });
