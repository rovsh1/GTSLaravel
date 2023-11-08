<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Event;

use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Shared\Event\OrderEventInterface;

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
