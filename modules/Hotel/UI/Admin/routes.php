<?php

use Illuminate\Support\Facades\Route;
use Module\Hotel\UI\Admin\Http\Controllers\HotelController;

Route::controller(HotelController::class)->prefix('hotel')->group(function () {
     Route::get('/', 'index');
});
