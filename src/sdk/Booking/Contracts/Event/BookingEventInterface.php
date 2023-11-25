<?php

namespace Sdk\Booking\Contracts\Event;

use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function bookingId(): BookingId;

    public function orderId(): OrderId;
}
