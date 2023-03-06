<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect(route('reference.country.index')))->name('home');

Route::group([], __DIR__ . '/auth.php');
Route::group([], __DIR__ . '/reference.php');
Route::group([], __DIR__ . '/hotel.php');
Route::group([], __DIR__ . '/filemanager.php');
Route::group([], __DIR__ . '/test.php');
