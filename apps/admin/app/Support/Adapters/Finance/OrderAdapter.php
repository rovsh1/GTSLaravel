<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Finance;

use Module\Client\Invoicing\Application\UseCase\GetWaitingPaymentOrders;

class OrderAdapter
{
    public function getWaitingPaymentOrders(int $paymentId): array
    {
        return app(GetWaitingPaymentOrders::class)->execute($paymentId);
    }
}
