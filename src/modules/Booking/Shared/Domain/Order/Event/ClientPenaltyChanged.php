<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Event;

use Module\Booking\Shared\Domain\Order\Order;
use Sdk\Booking\IntegrationEvent\ClientPenaltyChanged as IntegrationEvent;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

class ClientPenaltyChanged implements OrderEventInterface, HasIntegrationEventInterface
{
    public function __construct(
        public readonly Order $order
    ) {}

    public function orderId(): int
    {
        return $this->order->id()->value();
    }

    public function integrationEvent(): IntegrationEventInterface
    {
        return new IntegrationEvent($this->orderId());
    }
}
