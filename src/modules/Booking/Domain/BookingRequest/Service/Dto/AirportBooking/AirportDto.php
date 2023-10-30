<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\AirportBooking;

class AirportDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $director,
    ) {}
}
