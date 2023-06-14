<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Event\Request;

use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\BookingRequestEventInterface;

abstract class AbstractRequestEvent extends AbstractBookingEvent implements BookingRequestEventInterface
{
    public function __construct(
        AbstractBooking $booking,
        public readonly int $requestId,
    ) {
        parent::__construct($booking);
    }
}
