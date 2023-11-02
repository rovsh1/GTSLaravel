<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Event;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestId;
use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;
use Module\Booking\Domain\Shared\Event\BookingRequestEventInterface;

abstract class AbstractRequestEvent extends AbstractBookingEvent implements BookingRequestEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly RequestId $requestId,
    ) {
        parent::__construct($booking);
    }
}
