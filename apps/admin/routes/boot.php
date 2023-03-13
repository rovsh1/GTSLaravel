<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect(route('countries.index')))->name('home');

Route::group([], __DIR__ . '/auth.php');
Route::group([], __DIR__ . '/reservation.php');
Route::group([], __DIR__ . '/hotel.php');
Route::group([], __DIR__ . '/file-manager.php');
Route::group([], __DIR__ . '/test.php');
Route::group([], __DIR__ . '/administration.php');
Route::group([], __DIR__ . '/site.php');
Route::group([], __DIR__ . '/client.php');
Route::group([], __DIR__ . '/reports.php');
Route::group([], __DIR__ . '/finance.php');
