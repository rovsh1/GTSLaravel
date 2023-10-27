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
    ->resource('services', Supplier\Service\ServicesController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ])
    ->get(
        '/services/{type}/list',
        Supplier\Service\ServicesController::class . '@list',
        'read',
        'service.type.list'
    )
    ->get(
        '/services/{type}/settings',
        Supplier\Service\ServicesController::class . '@settings',
        'read',
        'service.type.settings'
    )
    ->get(
        '/services/{type}/settings/{serviceId}',
        Supplier\Service\ServicesController::class . '@settings',
        'read',
        'service.settings'
    )
    ->get(
        '/{supplier}/services/search',
        Supplier\Service\ServicesController::class . '@search',
        'read',
        'service.search'
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
    ->get('/{supplier}/cars/list', Supplier\CarController::class . '@list', 'read', 'cars.get')
    ->resource('airports', Supplier\AirportController::class, [
        'slug' => 'provider',
        'except' => ['show']
    ]);
