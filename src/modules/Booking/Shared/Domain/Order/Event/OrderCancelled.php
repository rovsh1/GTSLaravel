<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Event;

use Module\Booking\Shared\Domain\Order\Order;

class OrderCancelled implements OrderEventInterface
{
    public function __construct(
        public readonly Order $order
    ) {
    }

    public function orderId(): int
    {
        return $this->order->id()->value();
    }
}
