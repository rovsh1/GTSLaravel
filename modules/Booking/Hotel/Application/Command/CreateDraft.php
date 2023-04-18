<?php

namespace Module\Booking\Hotel\Application\Command;

class CreateDraft
{
    public function __construct(
        public readonly int $reservationId
    ) {}
}
