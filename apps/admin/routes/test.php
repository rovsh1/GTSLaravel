<?php

use Illuminate\Support\Facades\Route;

use App\Admin\Http\Controllers;

Route::controller(Controllers\TestController::class)->prefix('test')->group(function () {
    Route::match(['get', 'post'], '/form', 'form');
});
