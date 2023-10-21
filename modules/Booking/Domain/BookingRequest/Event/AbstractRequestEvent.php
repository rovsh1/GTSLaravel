<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Event;

use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;
use Module\Booking\Domain\Shared\Event\BookingRequestEventInterface;

abstract class AbstractRequestEvent extends AbstractBookingEvent implements BookingRequestEventInterface
{
    public function __construct(
        BookingInterface $booking,
        public readonly int $requestId,
    ) {
        parent::__construct($booking);
    }
}
