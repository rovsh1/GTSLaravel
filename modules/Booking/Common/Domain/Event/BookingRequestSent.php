<?php

namespace Module\Booking\Common\Domain\Event;

class BookingRequestSent implements EventInterface, StatusEventInterface, RequestEventInterface
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $requestId,
    ) {}
}
