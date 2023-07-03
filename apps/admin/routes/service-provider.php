<?php

use App\Admin\Http\Controllers\ServiceProvider;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('service-provider')
    ->resource('contacts', ServiceProvider\ContactController::class, [
        'except' => ['show', 'index']
    ])
    ->resource('seasons', ServiceProvider\SeasonController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->resource('services-transfer', ServiceProvider\Service\TransferServicesController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->get(
        '/{provider}/service-transfer/prices',
        ServiceProvider\Service\Price\TransferPricesController::class . '@index',
        'read',
        'service.transfer.prices.index'
    )
    ->get(
        '/{provider}/service-transfer/{service}/prices/get',
        ServiceProvider\Service\Price\TransferPricesController::class . '@getPrices',
        'read',
        'service.transfer.prices.get'
    )
    ->put(
        '/{provider}/service-transfer/{service}/price',
        ServiceProvider\Service\Price\TransferPricesController::class . '@update',
        'update',
        'service.transfer.price.update'
    )
    ->resource('services-airport', ServiceProvider\Service\AirportServicesController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->get(
        '/services-airport/search',
        ServiceProvider\Service\AirportServicesController::class . '@search',
        'read',
        'service.airport.search'
    )
    ->get(
        '/{provider}/service-airport/prices',
        ServiceProvider\Service\Price\AirportPricesController::class . '@index',
        'read',
        'service.airport.prices.index'
    )
    ->get(
        '/{provider}/service-airport/{service}/prices/get',
        ServiceProvider\Service\Price\AirportPricesController::class . '@getPrices',
        'read',
        'service.airport.prices.get'
    )
    ->put(
        '/{provider}/service-airport/{service}/price',
        ServiceProvider\Service\Price\AirportPricesController::class . '@update',
        'update',
        'service.airport.price.update'
    )
    ->resource('cars', ServiceProvider\CarController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->resource('airports', ServiceProvider\AirportController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ]);
