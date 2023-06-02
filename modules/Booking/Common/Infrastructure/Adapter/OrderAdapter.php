<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Adapter;

use Module\Booking\Common\Domain\Adapter\OrderAdapterInterface;
use Module\Booking\Order\Application\UseCase\CreateOrder;

class OrderAdapter implements OrderAdapterInterface
{
    public function createOrder(int $clientId): int
    {
        return app(CreateOrder::class)->execute($clientId);
    }
}
