<?php

use App\Admin\Http\Controllers\Supplier;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('supplier')
    ->resource('contacts', Supplier\ContactController::class, [
        'except' => ['show', 'index']
    ])
    ->resource('contracts', Supplier\ContractController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->resource('requisites', Supplier\RequisiteController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->resource('seasons', Supplier\SeasonController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->resource('services-transfer', Supplier\Service\TransferServicesController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->get(
        '/services-transfer/search',
        Supplier\Service\TransferServicesController::class . '@search',
        'read',
        'service.transfer.search'
    )
    ->get(
        '/{supplier}/services-transfer/list',
        Supplier\Service\TransferServicesController::class . '@list',
        'read',
        'service.transfer.list'
    )
    ->get(
        '/{provider}/service-transfer/prices',
        Supplier\Service\Price\TransferPricesController::class . '@index',
        'read',
        'service.transfer.prices.index'
    )
    ->get(
        '/{provider}/service-transfer/{service}/prices/get',
        Supplier\Service\Price\TransferPricesController::class . '@getPrices',
        'read',
        'service.transfer.prices.get'
    )
    ->put(
        '/{provider}/service-transfer/{service}/price',
        Supplier\Service\Price\TransferPricesController::class . '@update',
        'update',
        'service.transfer.price.update'
    )
    ->resource('services-airport', Supplier\Service\AirportServicesController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->get(
        '/services-airport/search',
        Supplier\Service\AirportServicesController::class . '@search',
        'read',
        'service.airport.search'
    )
    ->get(
        '/{supplier}/services-airport/list',
        Supplier\Service\AirportServicesController::class . '@list',
        'read',
        'service.airport.list'
    )
    ->get(
        '/{provider}/service-airport/prices',
        Supplier\Service\Price\AirportPricesController::class . '@index',
        'read',
        'service.airport.prices.index'
    )
    ->get(
        '/{provider}/service-airport/{service}/prices/get',
        Supplier\Service\Price\AirportPricesController::class . '@getPrices',
        'read',
        'service.airport.prices.get'
    )
    ->put(
        '/{provider}/service-airport/{service}/price',
        Supplier\Service\Price\AirportPricesController::class . '@update',
        'update',
        'service.airport.price.update'
    )
    ->resource('cars', Supplier\CarController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->resource('airports', Supplier\AirportController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ]);
