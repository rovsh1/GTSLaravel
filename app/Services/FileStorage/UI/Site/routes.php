<?php

use Illuminate\Support\Facades\Route;

use GTS\Services\FileStorage\UI\Site\Http\Controllers\FileController;

Route::controller(FileController::class)
    ->group(function () {
        Route::get('file/{guid}', 'file');
    });
