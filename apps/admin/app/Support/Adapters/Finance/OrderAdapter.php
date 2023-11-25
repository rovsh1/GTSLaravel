<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Finance;

use Module\Client\Invoicing\Application\UseCase\GetPaymentOrders;
use Module\Client\Invoicing\Application\UseCase\GetWaitingPaymentOrders;
use Module\Client\Payment\Application\RequestDto\LendPaymentRequestDto;
use Module\Client\Payment\Application\UseCase\LendPayment;

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
        //@todo переделать на разовый вызов
        foreach ($orders as $order) {
            $orderId = $order['id'];
            $sum = $order['sum'];
            app(LendPayment::class)->execute(new LendPaymentRequestDto($paymentId, $orderId, $sum));
        }
    }
}
