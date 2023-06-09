<?php

namespace Module\Booking\Common\Domain\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function bookingId(): int;

    public function orderId(): int;

    public function payload(): ?array;
}
