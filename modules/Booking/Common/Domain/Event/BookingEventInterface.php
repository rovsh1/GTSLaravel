<?php

namespace Module\Booking\Common\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function booking(): BookingInterface;

    public function bookingId(): int;

    public function orderId(): int;

    public function payload(): ?array;
}
