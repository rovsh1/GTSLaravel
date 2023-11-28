<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Finance;

use Module\Client\Invoicing\Application\UseCase\GetPaymentOrders;
use Module\Client\Invoicing\Application\UseCase\GetWaitingPaymentOrders;
use Module\Client\Payment\Application\RequestDto\LendOrderToPaymentRequestDto;
use Module\Client\Payment\Application\UseCase\OrdersLandingToPayment;

class OrderAdapter
{
    public function getWaitingPaymentOrders(int $paymentId): array
    {
        return app(GetWaitingPaymentOrders::class)->execute($paymentId);
    }

    public function getPaymentOrders(int $paymentId): array
    {
        return app(GetPaymentOrders::class)->execute($paymentId);
    }

    public function lendOrders(int $paymentId, array $orders): void
    {
        $ordersDto = array_map(fn(array $data) => new LendOrderToPaymentRequestDto(
            $data['id'],
            $data['sum'],
        ), $orders);

        app(OrdersLandingToPayment::class)->execute($paymentId, $ordersDto);
    }
}
