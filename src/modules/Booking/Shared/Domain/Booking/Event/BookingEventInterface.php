<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function bookingId(): BookingId;

    public function orderId(): OrderId;
}
