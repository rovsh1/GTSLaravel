<?php

namespace Sdk\Booking\Contracts\Event;

use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface BookingEventInterface extends DomainEventInterface, IntegrationEventInterface
{
    public function bookingId(): BookingId;
}
