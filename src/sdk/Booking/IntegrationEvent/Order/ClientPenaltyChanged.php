<?php

namespace Sdk\Booking\IntegrationEvent\Order;

use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

final class ClientPenaltyChanged implements IntegrationEventInterface
{
    public function __construct(
        public readonly int $orderId,
    ) {}
}
