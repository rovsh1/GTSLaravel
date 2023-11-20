<?php

use App\Admin\Http\Controllers\Supplier;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('supplier')
    ->get('/search', Supplier\SupplierController::class . '@search', 'read', 'search')
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
        '/{provider}/service-transfer/cancel-conditions',
        Supplier\Service\CancelConditions\TransferCancelConditionsController::class . '@index',
        'read',
        'service.transfer.cancel-conditions.index'
    )
    ->get(
        '/{provider}/service-transfer/cancel-conditions/get',
        Supplier\Service\CancelConditions\TransferCancelConditionsController::class . '@getAll',
        'read',
        'service.transfer.cancel-conditions.get-all'
    )
    ->get(
        '/{provider}/service-transfer/{service}/cancel-conditions',
        Supplier\Service\CancelConditions\TransferCancelConditionsController::class . '@get',
        'read',
        'service.transfer.cancel-conditions.get'
    )
    ->put(
        '/{provider}/service-transfer/{service}/cancel-conditions',
        Supplier\Service\CancelConditions\TransferCancelConditionsController::class . '@update',
        'update',
        'service.transfer.cancel-conditions.update'
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
    ->get(
        '/{provider}/service-other/prices',
        Supplier\Service\Price\OtherPricesController::class . '@index',
        'read',
        'service.other.prices.index'
    )
    ->get(
        '/{provider}/service-other/{service}/prices/get',
        Supplier\Service\Price\OtherPricesController::class . '@getPrices',
        'read',
        'service.other.prices.get'
    )
    ->put(
        '/{provider}/service-other/{service}/price',
        Supplier\Service\Price\OtherPricesController::class . '@update',
        'update',
        'service.other.price.update'
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
