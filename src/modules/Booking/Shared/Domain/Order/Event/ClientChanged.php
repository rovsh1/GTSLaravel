<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Event;

use Module\Booking\Shared\Domain\Order\Order;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;

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
