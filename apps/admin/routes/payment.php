<?php

declare(strict_types=1);

use App\Admin\Support\Facades\AclRoute;
use App\Admin\Http\Controllers;

AclRoute::for('payment')
    ->get('/{paymentId}/waiting-orders', Controllers\Finance\OrderController::class . '@getWaitingPaymentOrders', 'read', 'orders.waiting')
    ->get('/{paymentId}/orders', Controllers\Finance\OrderController::class . '@getPaymentOrders', 'read', 'orders')
    ->post('/{paymentId}/orders/lend', Controllers\Finance\OrderController::class . '@lendOrders', 'read', 'orders.lend')
;
