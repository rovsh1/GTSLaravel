<?php

namespace Sdk\Booking\Contracts\Entity;

use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Shared\Contracts\Support\SerializableInterface;

interface BookingPartInterface extends SerializableInterface
{
    public function bookingId(): BookingId;

    /**
     * @return DomainEventInterface[]
     */
    public function pullEvents(): array;
}
