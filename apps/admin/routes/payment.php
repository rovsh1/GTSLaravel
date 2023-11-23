<?php

declare(strict_types=1);

use App\Admin\Support\Facades\AclRoute;
use App\Admin\Http\Controllers;

AclRoute::for('payment')
    ->get('/{paymentId}/orders', Controllers\Finance\OrderController::class . '@getWaitingPaymentOrders', 'read', 'orders.search');
