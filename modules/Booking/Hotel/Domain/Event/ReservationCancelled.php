<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Common\Domain\Event\StatusEventInterface;

class ReservationCancelled implements EventInterface, StatusEventInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $cancellationNote = null,
    ) {}
}
