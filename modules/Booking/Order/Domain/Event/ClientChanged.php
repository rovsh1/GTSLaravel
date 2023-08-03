<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Event;

use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Common\Domain\Event\OrderEventInterface;
use Module\Booking\Order\Domain\Entity\Order;

class ClientChanged implements OrderEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly Order $order
    ) {}

    public function orderId(): int
    {
        return $this->order->id()->value();
    }

    public function bookingId(): int
    {
        // TODO: Implement bookingId() method.
    }
}
