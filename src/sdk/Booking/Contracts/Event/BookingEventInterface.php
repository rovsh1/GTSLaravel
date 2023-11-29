<?php

namespace Sdk\Booking\Contracts\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function booking(): Booking;

    public function bookingId(): BookingId;

    public function orderId(): OrderId;
}
