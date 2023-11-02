<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto\ServiceBooking;

class AirportInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $cityId,
    ) {}
}
