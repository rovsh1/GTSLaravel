<?php

namespace Module\Booking\Domain\Shared\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function bookingId(): int;

    public function orderId(): int;

    public function payload(): ?array;
}
