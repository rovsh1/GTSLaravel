<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect(route('countries.index')))->name('home');

Route::group([], __DIR__ . '/auth.php');
Route::group([], __DIR__ . '/hotel.php');
Route::group([], __DIR__ . '/file-manager.php');
