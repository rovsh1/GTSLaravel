<?php

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

// hotel
AclRoute::resource('hotel', Controllers\Hotel\HotelController::class)
    ->resource('rooms', Controllers\Hotel\RoomController::class, ['except' => ['show']]);

//main
AclRoute::resource('hotel-quota', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-user', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-discount', Controllers\Data\CountryController::class, ['except' => ['show']]);
AclRoute::resource('hotel-price-list', Controllers\Hotel\PriceListController::class, ['except' => ['show']]);

// reference
AclRoute::resource('hotel-service', Controllers\Hotel\Reference\ServiceController::class, ['except' => ['show']]);
AclRoute::resource('hotel-usability', Controllers\Hotel\Reference\UsabilityController::class, ['except' => ['show']]);

// additional
AclRoute::resource('hotel-service-type', Controllers\Hotel\Reference\ServiceTypeController::class, ['except' => ['show']]);
AclRoute::resource('hotel-room-name', Controllers\Hotel\Reference\RoomNameController::class, ['except' => ['show']]);
AclRoute::resource('hotel-room-type', Controllers\Hotel\Reference\RoomTypeController::class, ['except' => ['show']]);
AclRoute::resource('hotel-bed-type', Controllers\Hotel\Reference\BedTypeController::class, ['except' => ['show']]);
AclRoute::resource('hotel-type', Controllers\Hotel\Reference\TypeController::class, ['except' => ['show']]);
AclRoute::resource('hotel-usability-group', Controllers\Hotel\Reference\UsabilityGroupController::class, ['except' => ['show']]);
