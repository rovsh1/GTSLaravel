<?php

use App\Hotel\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::controller(ImageController::class)
    ->prefix('images')
    ->as('images.')
    ->group(function () {
        Route::get('/', 'index')->name('index');

        Route::get('/{hotel}/images', Controllers\Hotel\ImageController::class . '@index', 'update', 'images.index');
        Route::get('/{hotel}/images/get', Controllers\Hotel\ImageController::class . '@get', 'update', 'images.get');
        Route::post(
            '/{hotel}/images/upload',
            Controllers\Hotel\ImageController::class . '@upload',
            'update',
            'images.upload'
        );
        Route::delete(
            '/{hotel}/images/{image}',
            Controllers\Hotel\ImageController::class . '@destroy',
            'update',
            'images.destroy'
        );
        Route::post(
            '/{hotel}/images/reorder',
            Controllers\Hotel\ImageController::class . '@reorder',
            'update',
            'images.reorder'
        );
        Route::get(
            '/{hotel}/images/{image}/rooms',
            Controllers\Hotel\ImageController::class . '@getImageRooms',
            'update',
            'images.rooms'
        );
        Route::post(
            '/{hotel}/images/{image}/main/set',
            Controllers\Hotel\ImageController::class . '@setMainImage',
            'update',
            'images.main.set'
        );
        Route::post(
            '/{hotel}/images/{image}/main/unset',
            Controllers\Hotel\ImageController::class . '@unsetMainImage',
            'update',
            'images.main.unset'
        );

        Route::get(
            '/{hotel}/images/{room}/list',
            Controllers\Hotel\ImageController::class . '@getRoomImages',
            'update',
            'images.room.get'
        );
        Route::post(
            '/{hotel}/rooms/{room}/images/{image}/set',
            Controllers\Hotel\ImageController::class . '@setRoomImage',
            'update',
            'images.room.set'
        );
        Route::post(
            '/{hotel}/rooms/{room}/images/{image}/unset',
            Controllers\Hotel\ImageController::class . '@unsetRoomImage',
            'update',
            'images.room.unset'
        );
        Route::post(
            '/{hotel}/rooms/{room}/images/reorder',
            Controllers\Hotel\ImageController::class . '@reorderRoomImages',
            'update',
            'images.room.reorder'
        );
    });
