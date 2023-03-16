<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

// hotel
AclRoute::resource('hotel', Controllers\Hotel\HotelController::class)
    ->resource('rooms', Controllers\Hotel\RoomController::class, ['except' => ['show']]);

//main
AclRoute::resource('hotel-quota', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-user', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-discount', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-price-list', Controllers\Hotel\PriceListController::class, ['except' => ['show']]);

// reference
AclRoute::resource('hotel-service', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-usability', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-landmark', Controllers\Reference\CountryController::class, ['except' => ['show']]);

// additional
AclRoute::resource('hotel-landmark-type', Controllers\Reference\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-room-name', Controllers\Hotel\RoomNameController::class, ['except' => ['show']]);
AclRoute::resource('hotel-room-type', Controllers\Hotel\RoomTypeController::class, ['except' => ['show']]);
AclRoute::resource('hotel-bed-type', Controllers\Hotel\BedTypeController::class, ['except' => ['show']]);
AclRoute::resource('hotel-type', Controllers\Hotel\TypeController::class, ['except' => ['show']]);
AclRoute::resource('hotel-usability-group', Controllers\Hotel\UsabilityGroupController::class, ['except' => ['show']]);
