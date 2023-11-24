<?php

declare(strict_types=1);

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('payment')
    ->get('/{id}', Controllers\Finance\PaymentController::class . '@get', 'read', 'get')
    ->get('/{paymentId}/waiting-orders', Controllers\Finance\OrderController::class . '@getWaitingPaymentOrders', 'read', 'orders.waiting')
    ->get('/{paymentId}/orders', Controllers\Finance\OrderController::class . '@getPaymentOrders', 'read', 'orders')
    ->post('/{paymentId}/orders/lend', Controllers\Finance\OrderController::class . '@lendOrders', 'read', 'orders.lend');
