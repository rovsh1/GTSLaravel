<?php

namespace Sdk\Booking\IntegrationEvent\Order;

use Sdk\Shared\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

final class OrderRefunded implements IntegrationEventInterface
{
    public function __construct(
        public readonly int $orderId,
        public readonly OrderStatusEnum $status,
    ) {}
}
