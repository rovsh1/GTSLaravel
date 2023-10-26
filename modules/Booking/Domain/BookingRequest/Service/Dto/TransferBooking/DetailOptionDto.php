<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking;

class DetailOptionDto
{
    public function __construct(
        public readonly string $label,
        public readonly mixed $value
    ) {}
}
