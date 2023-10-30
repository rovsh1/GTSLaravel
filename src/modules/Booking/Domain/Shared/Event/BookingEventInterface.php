<?php

namespace Module\Booking\Domain\Shared\Event;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function bookingId(): BookingId;

    public function orderId(): OrderId;

    public function payload(): ?array;
}
