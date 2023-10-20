<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\AirportBooking;

class ServiceDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $type,
    ) {}
}
