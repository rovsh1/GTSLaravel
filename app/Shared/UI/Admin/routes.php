<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect(route('currency.index')))->name('index');
