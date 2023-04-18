<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Common\Domain\Event\RequestEventInterface;
use Module\Booking\Common\Domain\Event\StatusEventInterface;

class ReservationRequestSent implements EventInterface, StatusEventInterface, RequestEventInterface
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $requestId,
        public readonly int $fileGuid,
    ) {}
}
