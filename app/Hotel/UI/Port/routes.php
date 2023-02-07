<?php

use GTS\Hotel\UI\Port\Controllers\InfoController;

$this->register('findById', [InfoController::class, 'findById']);
$this->register('getRoomsWithPriceRatesByHotelId', [InfoController::class, 'getRoomsWithPriceRatesByHotelId']);
