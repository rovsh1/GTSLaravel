<?php

declare(strict_types=1);

use App\Admin\Http\Controllers;
use App\Admin\Support\Facades\AclRoute;

AclRoute::for('payment')
    ->get('/{id}', Controllers\Client\PaymentController::class . '@get', 'read', 'get')
    ->get('/{paymentId}/waiting-orders', Controllers\Client\OrderController::class . '@getWaitingPaymentOrders', 'read', 'orders.waiting')
    ->get('/{paymentId}/orders', Controllers\Client\OrderController::class . '@getPaymentOrders', 'read', 'orders')
    ->post('/{paymentId}/orders/lend', Controllers\Client\OrderController::class . '@lendOrders', 'read', 'orders.lend');


AclRoute::for('supplier-payment')
    ->get('/{id}', Controllers\Supplier\PaymentController::class . '@get', 'read', 'get')
    ->get('/{paymentId}/waiting-bookings', Controllers\Supplier\BookingController::class . '@getWaitingPaymentBookings', 'read', 'bookings.waiting')
    ->get('/{paymentId}/bookings', Controllers\Supplier\BookingController::class . '@getPaymentBookings', 'read', 'bookings')
    ->post('/{paymentId}/bookings/lend', Controllers\Supplier\BookingController::class . '@lendBookings', 'read', 'bookings.lend');
