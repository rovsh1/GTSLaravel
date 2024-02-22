<?php

use Illuminate\Support\Facades\Route;

use GTS\Hotel\Interface\Admin\Http\Controllers\HotelController;

Route::controller(HotelController::class)->prefix('hotel')->group(function () {
     Route::get('/', 'index');
});
